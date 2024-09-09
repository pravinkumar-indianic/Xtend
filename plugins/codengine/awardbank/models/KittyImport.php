<?php 
namespace Codengine\Awardbank\Models;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KittyImport extends \Backend\Models\ImportModel
{
    /**
     * @var array The rules to be applied to the data.
     */
    public $rules = [];

    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {

            try {
                $kitty = new Kitty;
                $kitty->fill($data);
                $kitty->save();

                $this->logCreated();
            }
            catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }

        }
    }
}