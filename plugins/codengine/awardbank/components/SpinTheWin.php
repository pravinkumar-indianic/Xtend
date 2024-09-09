<?php namespace Codengine\Awardbank\Components;

use Addgod\MandrillTemplate\Mandrill\Message;
use Addgod\MandrillTemplate\Mandrill\Recipient;
use Addgod\MandrillTemplate\Mandrill\Template;
use Addgod\MandrillTemplate\MandrillTemplateFacade;
use Codengine\Awardbank\Models\SpinTheWinPrice;
use Codengine\Awardbank\Models\SpinTheWinResult;
use Codengine\Awardbank\Models\SpinTheWinSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RainLab\User\Models\User;
use Codengine\Awardbank\Models\Program;
use \Cms\Classes\ComponentBase;
use Storage;
use Config;
use Auth;
use Redirect;
use stdClass;
use System\Models\File;

/**
 * Class SpinTheWin
 * TODO
 * - Images for price
 * - User #spin management
 */

class SpinTheWin extends ComponentBase
{
    private $user;
    private $program;

    public function componentDetails()
    {
        return [
            'name' => 'Spin The Win',
            'description' => 'Spin The Win',
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }


    public function init()
    {
        $this->user = Auth::getUser();
        if ($this->user && $this->user->currentProgram) {
            $this->program = $this->user->currentProgram;
        }
    }

    public function onRun()
    {
        $this->coreLoadSequence();
    }

    /**
    Reusable function call the core sequence of functions to load and render the Cart partial html
     **/

    public function coreLoadSequence()
    {
        $this->generateHtml();
    }

    public function generateHtml()
    {
        $this->page['user'] = $this->user;
        $this->page['month'] = $this->month;
        $this->page['enabled'] = $this->checkIfEnabled();
        $this->page['user_can_spin'] = $this->checkIfUserCanSpin();
        $this->page['prize_colors'] = $this->getPrizeColors();
        if ($this->page['enabled']) {
            $this->page['json_data'] = $this->getJsonData();
            $this->page['available_spins_count'] = $this->getAvailableSpinsCount();
        }
    }

    private function checkIfEnabled()
    {
        $activeForCurrentProgram = $this->checkProgramSpecificRules();
        if (!$activeForCurrentProgram) {
            return false;
        }

        $enabledSetting = SpinTheWinSettings::where('program_id', '=' , $this->program->id)
            ->whereIn('key', ['enabled', 'start_date', 'end_date'])
            ->get();

        $settings = [];

        foreach ($enabledSetting as $parameter) {
            $settings[$parameter->key] = $parameter->value;
        }

        if (!empty($settings['enabled'])) {
            if (!empty($settings['start_date']) && !empty($settings['end_date'])) {
                $timezone = new \DateTimeZone('Australia/Sydney');

                $startDateTime = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $settings['start_date'] . ' 00:00:00',
                    $timezone
                )->getTimestamp();

                $endDateTime = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $settings['end_date'] . ' 23:59:59',
                    $timezone
                )->getTimestamp();

                $currentDateTime = new \DateTime();
                $currentDateTime->setTimezone($timezone);
                $currentDateTime = $currentDateTime->getTimestamp();

                if ($startDateTime <= $currentDateTime && $currentDateTime <= $endDateTime) {
                    return true;
                }
            }
        }

        return false;
    }

    private function checkProgramSpecificRules()
    {
        //For ASE 2023 Program check the targeting tags first
        if ($this->program->id == 112) {
            $active = false;

            if (!empty($this->user->targetingtags)) {
                foreach ($this->user->targetingtags as $tag) {
                    if ($tag->name == 'Parts') {
                        $active = true;
                    }
                }
            }

            return $active;
        }

        return true;
    }

    private function checkIfUserCanSpin()
    {
        switch ($this->program->id) {
            case 106 : return $this->user->roll == 'Member Store';
        }

        return true;
    }

    private function getPrizeColors()
    {
        switch ($this->program->id) {
            case 106 : return "'a9cbd9', '4db5ce', 'f2c288', 'f29c94', '0388a6'";
        }

        return "'EB0A1E', '808080', 'cccccc', 'EB0A1E', '808080', 'cccccc'";
    }

    private function getJsonData()
    {
        $prices = $this->getPrices();

        $data = [];
        $index = 1;
        foreach ($prices as $price) {
            $data[] = [
                'label' => $price->label,
                'name' => $price->name,
                'message' => $price->message,
                'is_price' => $price->is_price,
                'value' => $price->id,
                'available' => $price->quantity > 0,
                'img_path' => $price->logo ? $price->logo->getThumb(200,200,'fit') : ''
            ];

            $index++;
        }

        return json_encode($data);
    }

    private function getPrices()
    {
        return SpinTheWinPrice::where('program_id', '=', $this->program->id)
            //->where('quantity', '>', 0)
            ->orderBy('id')
            ->get();
    }

    private function getAvailableSpinsCount()
    {
        if (!empty($this->user->id)) {
            $userDetails = SpinTheWinSettings::where('key', '=', 'userdetails')
                ->where('program_id', '=', $this->program->id)
                ->first();

            if ($userDetails) {
                $userDetails = json_decode($userDetails->value, true);
                return $userDetails[$this->user->id] ?? 0;
            }
        }

        return 0;
    }

    public function onGetRandomPrice()
    {
        $availableSpins = $this->getAvailableSpinsCount();
        if (!$availableSpins) {
            return false;
        }

        $price = $this->getRandomPrice();
        if ($price) {
            //decrease price quantity/availability
            if ($price->quantity > 0) {
                $price->quantity = $price->quantity - 1;
            }

            $result = new SpinTheWinResult();
            $result->price_id = $price->id;
            $result->user_id = $this->user->id;

            //Detect free spins prices
            $addSpins = 0;
            if (stripos($price->name, 'free spin') !== false) {
                $addSpins = intval(substr($price->name, 0, strspn($price->name, "0123456789")));
            }

            //Update number of available spins
            $userDetails = SpinTheWinSettings::where('key', '=', 'userdetails')
                ->where('program_id', '=', $this->program->id)
                ->first();

            $spinsList = json_decode($userDetails->value, true);
            if (!empty($spinsList) && !empty($this->user->id)) {
                if (isset($spinsList[$this->user->id])) {
                    $spinsList[$this->user->id] = $spinsList[$this->user->id] - 1 + $addSpins;
                } else {
                    $spinsList[$this->user->id] = 0 + $addSpins;
                }

                $userDetails->value = json_encode($spinsList);

                if ($price->save() && $result->save() && $userDetails->save()) {
                    //send notification email if an actual prize
                    if ($price->is_price) {
                        try {
                            $this->sendNotificationEmail($price);
                        } catch (\Exception $e) {
                            Log::error($e->getMessage() . ' ' . $e->getTraceAsString());
                        } catch (\Throwable $e) {
                            Log::error($e->getMessage() . ' ' . $e->getTraceAsString());
                        }
                    }

                    return json_encode(['result' => $price->id, 'addspins' => $addSpins]);
                }
            }
        }

        return json_encode(false);
    }

    private function getRandomPrice()
    {
        $priceRanges = $this->buildPriceQueue();
        if (!empty($priceRanges['ranges']) && !empty($priceRanges['max'])) {
            $randomPriceKey = rand(0, $priceRanges['max']);
            foreach ($priceRanges['ranges'] as $range) {
                if ($randomPriceKey >= $range['min'] && $randomPriceKey <= $range['max']) {
                    return SpinTheWinPrice::find($range['id']);
                }
            }
        }

        return false;
    }

    private function buildPriceQueue()
    {
        $price_ranges = [];
        $multiplier = 1000;
        $multiplierDecimals = 2;

        $prices = SpinTheWinPrice::where('program_id', '=', $this->program->id)
            ->where('quantity', '>' , 0)
            ->get()
            ->toArray();

        if (!empty($prices)) {
            $queue = [];
            //1. Convert probabilities total sum to 100
            //e.g. relative prob sum 18, prob 1 => 1% then recalculate the prob as (1/18) * 100
            $relativeSum = 0;
            foreach ($prices as $price) {
                $relativeSum += $price['probability'];
            }

            foreach ($prices as $key => $price) {
                if ($price['probability'] > 0) {
                    $prices[$key]['probability'] = round(($price['probability'] / $relativeSum) * 100, $multiplierDecimals);
                }
            }

            //Sort by priority asc
            usort($prices, function($a, $b) {
               if ($a['priority'] == $b['priority']) {
                   return 0;
               }
               return ($a['priority'] < $b['priority']) ? -1 : 1;
            });

            $index = 0;
            foreach ($prices as $key => $price) {
                if ($price['probability'] > 0) {
                    $price_ranges[$key] = [
                        'id' => $price['id'],
                        'min' => $index * $multiplier,
                        'max' => (round($index + $price['probability'] - (1 / $multiplier), 3)) * $multiplier
                    ];

                    $index += $price['probability'];
                }
            }
        }

        return [
            'ranges' => $price_ranges,
            'min' => 0,
            'max' => $index ? ($index * $multiplier) : 1
         ];
    }

    private function sendNotificationEmail($price)
    {
        if (!empty($this->user->email)) {
            if (env('APP_ENV') == 'staging') {
                $toemails = ['hq@evtmarketing.com.au'];
            } else {
                $toemails = [$this->user->email];
            }

        $vars = [
            'receiverfirstname' => empty($this->user->business_name) ?
                (empty($this->user->name) ? ($this->user->full_name)
                    : $this->user->name)
                : $this->user->business_name,
            'pricename' => $price->name,
        ];

        if (!is_null($price->logo)) {
            $vars['prizeimageurl'] = $price->logo->getThumb(200,200,'fit');
        }

            if (env('APP_ENV') == 'production') {
                $ccemails = ['hq@evtmarketing.com.au'];
            } else {
                $ccemails = [];
            }
            $template = $this->getNotificationEmailTemplate();
            $subject = $this->getNotificationEmailSubject();

            if (!empty($template) && !empty($subject)) {
                $template = new Template($template);
                $message = new Message();
                if (!empty($subject)) {
                    $message->setSubject($subject);
                }

                $message->setFromEmail($this->getNotificationEmailSender());
                $message->setMergeVars($vars);

                foreach ($toemails as $toemail) {
                    $recipient = new Recipient($toemail, null, Recipient\Type::TO);
                    $recipient->setMergeVars($vars);
                    $message->addRecipient($recipient);
                }

                foreach ($ccemails as $ccemail) {
                    $ccRecipient = new Recipient($ccemail, null, Recipient\Type::CC);
                    $message->setMergeVars($vars);
                    $message->addRecipient($ccRecipient);
                }

                MandrillTemplateFacade::send($template, $message);
            }
        }
    }

    private function getNotificationEmailTemplate()
    {
        switch ($this->program->id) {
            case 106 : return '5step-spin-to-win-email-xtend-2-0';
            case 112 : return 'ase-spin-to-win-email-template-xtend-2-0';
        }

        return false;
    }

    private function getNotificationEmailSubject()
    {
        switch ($this->program->id) {
            case 106 : return '5STEP Incentive - Congratulations! Claim your Spin to Win prize';
            case 112 : return 'AfterSales Elite 2023 - Congratulations! Claim your Spin to Win prize';
        }

        return false;
    }

    private function getNotificationEmailSender()
    {
        switch($this->program->id) {
            case 106 : return 'incentive@5STEP.com.au';
            case 112 :
            default: return 'hq@evtmarketing.com.au';
        }
    }

    private function testRandom($price_range, $prices)
    {
        $results = [];

        for ($i = 0; $i <= 1000000; $i++) {
            $rand = rand(0, 100000);
            foreach ($price_range as $key => $range) {
                if (($rand >= $range['min']) && ($rand <= $range['max'])) {
                    if (!isset($results[$key])) {
                        $results[$key] = 1;
                    } else {
                        $results[$key] += 1;
                    }
                }
            }
        }

        ksort($results);

        dd($results, $prices);
    }
}
