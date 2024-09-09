<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Product as Product;
use Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {

        $product = Product::create([
            'name'                 => 'Sennheiser HD 202 Closed Headphones',
            'supplier_id'                 => '1',
           'model_number' => '504291',
           'brand' => 'SENNHEISER',
           'category-string' => 'Headphones', 
           'category-string-check' => true,                
            'description' => '
              Engineered for discerning business travellers and frequent flyers. these premium ear canal phones feature the latest incarnation of Sennheiserâ€™s active noise cancelling technology NoiseGard digital â€“ which identifies and reduces even more unwanted ambient noise. Moreover. its tri mode design allows you to select from one of three different digital noise cancellation modes based on different acoustic environments.

              The CXC 700 are also equipped with TalkThrough function. which mutes the audio input and allows you to listen to your external environment. enabling you to communicate with the flight attendant or your fellow travel mate without removing the ear canal phones.

              Their sleek and ergonomic control box houses a single AAA alkaline battery that lasts for up to 16 hours of continuous usage. The intuitive controls for NoiseGard digital. TalkThrough and volume level have been conveniently located so as to allow for quick and easy single handed adjustments. even in low light conditions such as on an aeroplane.

              These versatile ear canal phones are suitable for listening to all types of audio content from both in flight entertainment systems as well as most types of media devices.

              Key Features

              NoiseGard digital Active Noise Cancellation: Choice of 3 different modes to suit your listening environment.

              TalkThrough function enables you to communicate even without removing the earphones

              Highly advanced acoustic system for excellent audio performance

              Excellent passive attenuation of ambient noise of 25 dB (for frequency range above 3000 Hz)

              Integrated control box enhanced usability and convenience

              Sleek ergonomic design with striking metalized components for a modern. sophisticated look

              Long lasting NoiseGardâ„¢ / digital usage a single alkaline battery lasts up to 16 hours!

              Audio transmission always works â€“ even in passive mode and without the batteries

              Convenient storage pouch and cleaning tool included

              Optimised for in flight entertainment systems as well as MP3. iPod. iPhone. iPad and portable media players

              2 year warranty

              Attenuation: 23 dB / 25 dB (Active/Passive)

              Frequency response: 20 21000 Hz

              Impedance: 45 300 ?

              Sound pressure level (SPL): 108 dB at 1kHz"
              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/3585.jpg',
            'cost_ex' => 57.11,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 79.95,
            'mark_up_override' => true,
            'mark_up_integer' => 20,
            'mark_up_type' => 'percent',
            'active' => 1,

        ]);

        $product->tags()->attach(1);
        //$product->tag()->attach(2);

        $product2 = Product::create([
            'name'                 => 'SENNHEISER - RS120-9 II WIRELESS RF HPHN',
            'supplier_id'                 => '1',
           'model_number' => '504999',
           'brand' => 'SENNHEISER',
           'category-string' => 'Headphones', 
           'category-string-check' => true,                              
            'description' => 'The RS 120 is an open. supra aural wireless RF headphone system. Its transparent and well balanced sound with great bass response makes this system an ideal choice for all types of music and TV applications. Furthermore. its innovative easy recharge function offers the ultimate in convenience when it comes to charging and storing your wireless headphones. Enjoy total freedom of sound. stylish design and maximum comfort. all at an affordable price.',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/14174.jpg ',
            'cost_ex' => 108.00,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 229.00,
            'mark_up_override' => false,
            'mark_up_integer' => 5,
            'mark_up_type' => 'percent',
            'active' => 1,

        ]);
        $product2->tags()->attach(1);

        $product3 = Product::create([
            'name'                 => 'BELKIN - IPOD/IPHON 3.5M-3.5M AUDIO CABLE',
            'supplier_id'                 =>  1,
           'model_number' => 'F8Z181-06-GLD',
           'brand' => 'BELKIN',
           'category-string' => 'Audio Accessories',   
           'category-string-check' => true,                            
            'description' => 'High quality chrome 1.8m cable for connecting iPod/iPhone to portable speakers, sound cards, stereos with 3.5mm plug.',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/15135.jpg',
            'cost_ex' => 10.25,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 19.95,
            'mark_up_override' => true,
            'mark_up_integer' => 10,
            'mark_up_type' => 'dollar',     
            'active' => 1,

        ]);  
        $product3->tags()->attach(1);
        
         $product4 = Product::create([
            'name'                 => 'SHERWOOD - PM9805 (CT2)',
            'supplier_id'                 => 1,
           'model_number' => 'PM9805',
           'brand' => 'SHERWOOD',
           'category-string' => 'Turntables',  
           'category-string-check' => true,                             
            'description' => 'Professional Quick-Start Hi-Fi Turntable.',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/15566.jpg',
            'cost_ex' => 171.45,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 249.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',  
            'active' => 1,                          
        ]); 
        $product4->tags()->attach(1);
        
        $product5 = Product::create([
            'name'                 => 'Canon EF 17-40mm f/4L USM Lens',
            'supplier_id'                 =>  1,
           'model_number' => 'EF17-40L',
           'brand' => 'CANON',
           'category-string' => 'Camera Lens',  
           'category-string-check' => true,                             
            'description' => 'Small and lightweight for its class. the EF 17 40mm f/4L USM combines outstanding L series build quality with an affordable price tag. Its size makes it easy to carry and comfortable to use. while its versatility and superior optical performance makes it a great general purpose lens. As a landscape lens. the EF 17 40mm f/4L USM features a ring type ultrasonic motor for superfast. accurate autofocus in near silence. as well as wide open centre and corner sharpness for finely detailed images. Its seven blade circular aperture delivers excellent OOF (Out of Focus) blur quality. while the lens itself offers superb full frame compatibility and performance. This is a great choice if you are interested in landscape photography or real estate and architecture photography. and is especially handy if you need to work in a tight space!

              Key Features

              Ultra Wide Lens for Analogue EOS SLR Cameras
              Ultra wide angle Canon EF zoom lens meant for professional and high amateur SLR EOS camera users.

              Standard Zoom Lens for Digital EOS SLR Cameras
              This can give an angle of view equivalent to that of a 28 70mm lens.

              Superior Optical Performance
              Super Spectra Coating on the elements ensures colour balance. minimises ghosting & flare to provide image quality.

              Extensive Set of High End Features
              The AF works silently and quickly thanks to a fast central processing unit.',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/19233.jpg',
            'cost_ex' => 974.25,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 1249.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',   
            'active' => 1,                        
        ]); 
        $product5->tags()->attach(2);
        
        $product6 = Product::create([
            'name'                 => 'CANON - EFS 10-22 f3.5-4.5 USM',
            'supplier_id'                 =>  1,
           'model_number' => 'EFS10-22U',
           'brand' => 'CANON',
           'category-string' => 'Camera Lens',  
           'category-string-check' => true,                             
            'description' => 'Canon EF-S 10-22mm f/3.5-4.5 USM Lens',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/19233.jpg',
            'cost_ex' => 706.42,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 899.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',   
            'active' => 1,                        
        ]);
        $product6->tags()->attach(3);

        $product7 = Product::create([
            'name'                 => 'Nikon Nikkor AF-S 50mm f1.4G Lens',
            'supplier_id'                 =>  1,
           'model_number' => 'JAA014DA',
           'brand' => 'NIKON',
           'category-string' => 'Camera Lens',  
           'category-string-check' => true,                             
            'description' => '
              This latest addition to the NIKKOR lineup features a large maximum aperture of f/1.4. enabling it to be used for easy handheld shooting in dark settings. such as a dimly lit room. It also allows photographers to easily create beautiful large blur effects. The lensâ€™ Silent Wave Motor (SWM) ensures quick. quiet autofocus. Autofocus shooting is made possible with SLR models that do not have a built in motor. such as the D40 series and D60.

              The AF S NIKKOR 50mm f/1.4G is a high performance large aperture. single focal length lens especially suitable for professional and advanced amateurs who use Nikon FX format cameras such as the D3 and D700. and frequently shoot human subjects as well as night landscapes and astronomy. Whatâ€™s more. when attached to a DX format SLR. this f/1.4 lens with 75mm equivalent picture angle is capable of shooting portraits with beautiful blur effects.


              Key Features

              Lens Construction (Elements/Groups)
              8/7

              Picture Angle with 35mm (135) format
              46Â°

              Picture Angle with Nikon DX Format
              31Â° 30

              Minimum f/stop
              16

              Closest focusing distance
              0.45m

              Maximum reproduction ratio
              0.15x

              Filter Attachment Size
              58mm

              Lens Cap
              Snap on

              Lens Hood
              HB 47

              Lens Case
              CL 1013

              Dimensions (approx.) (from the cameraÃ¢â‚¬â„¢s lens mounting flange)
              Approx. 73.5 x 54 mm

              Weight (approx.)
              Approx. 280 g

              Supplied Accessories (may differ by country or area)
              Front lens cap LC 58. Rear lens cap LF 1. Bayonet hood HB 47. Flexible lens pouch CL 1013making the D7200 your trusted companion. Its long lasting battery life lets you shoot up to 1110 images or 80 minutes of movies at a go. Rough it out with this compact and portable performer which weighs approximately 1250g."
              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/22995.jpg',
            'cost_ex' => 485.45,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 539.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',    
            'active' => 1,                       
        ]);
        $product7->tags()->attach(3);

        $product8 = Product::create([
            'name'                 => 'Breville the Shake Creation Milkshake Maker',
            'supplier_id'                 =>  1,
           'model_number' => 'MS400D',
           'brand' => 'BREVILLE',
           'category-string' => 'Snack Makers',   
           'category-string-check' => true,                            
            'description' => '
             "Overview

              There\'s nothing like a cold milkshake on a hot summer\'s day but a good old fashion one can be hard to find these days.

              Breville\'s Shake Creationsâ„¢ keeps the tradition of the milk bar alive in the comfort of your kitchen.  The double aerating motor whips through milk and ice cream for a beautifully smooth. frothy shake.  The shiny chrome design and stainless steel cup are not only durable. but are sure to take you and your family on a trip back in time.

              Key Features

              Classic chrome design

              Extra large stainless steel cup

              Powerful high speed motor for faster. superior whipping results

              Unique double aerator mixing blade ensures superior frothing"

              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/23827.jpg',
            'cost_ex' => 38.40,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 59.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',      
            'active' => 1,                     
        ]);
        $product8->tags()->attach(1); 

        $product9 = Product::create([
            'name'                 => 'NIKON - AF-S DX 35mm f1.8G',
            'supplier_id'                 =>  1,
           'model_number' => 'JAA132DA',
           'brand' => 'NIKON',
           'category-string' => 'Camera Lens', 
           'category-string-check' => true,                              
            'description' => '
              Nikon AF-S DX Nikkor 35mm f/1.8G Lens
              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/24106.jpg',
            'cost_ex' => 221.66,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 299.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',  
            'active' => 1,                         
        ]);
        $product9->tags()->attach(1);  

        $product10 = Product::create([
            'name'                 => 'Canon EF 70-200mm f/2.8L USM Lens',
            'supplier_id'                 =>  1,
           'model_number' => 'EF 70-200LU',
           'brand' => 'CANON',
           'category-string' => 'Camera Lens',
           'category-string-check' => true,                               
            'description' => '
" 
Overview

Fast. accurate and superbly built. the EF 70 200mm f/2.8L USM is the ideal sports photography lens. Featuring one of the most useful and most popular focal length ranges available. this telephoto lens not only excels in sports photography. it also allows you to shoot outstanding landscapes and portraits. while being equally comfortable capturing candid moments with friends and family. With its fast. accurate ring type USM autofocus and internal focusing. it is incredibly easy to find the focus of your subject. while the lensâ€™ excellent build quality makes it comfortable to carry and to hold. Optical performance is exceptional. helping you capture images that are sharp corner to corner. with beautiful colour and contrast. and well controlled chromatic aberration. distortion and vignetting. Whether you want to catch the action on or off the field. or if you want to capture a sweeping landscape or a dramatic portrait. the EF 70 200mm f/2.8L USM should be an essential part of your kit.


Key Features

Zoom as Good as a Prime Lens
Chromatic aberrations & multiple zoom groups allow internal focusing.

Fast Constant Maximum Aperture
Shoot hand held in low light conditions thanks to a fast f/2.8 maximum aperture.

L Series Quality
The lens embodies Canon\'s highest standards of L series optics.

Ultrasonic Focusing
A ring type ultrasonic motor drives autofocus quickly. and in near silence.

Super Spectra Coating
Ensure accurate colour balance & high contrast. suppress flare & ghosting by absorbing light."

              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://assets2.jbhifi.com.au/24292.jpg',
            'cost_ex' => 1479.44,
            'cost_freight' => 11.80,
            'cost_tax_percent' => 10,
            'cost_rrp' => 1688.00,
            'mark_up_override' => false,
            'mark_up_integer' => 10,
            'mark_up_type' => 'percent',    
            'active' => 1,                       
        ]);
        $product10->tags()->attach(1);    

        $product11 = Product::create([
            'name'                 => 'Panasonic - 27 Litre Convection Microwave - Stainless Steel',
            'supplier_id'                 =>  2,
           'model_number' => 'NNCF770M',
           'brand' => 'Panasonic',
           'category-string' => 'Microwaves',
           'category-string-check' => true,                               
            'description' => '
" 
 Featuring flatbed and inverter technology and 15 automatic cooking menus  the Panasonic 27L Convection Flatbed Microwave functions as a microwave  convection oven and grill so you have the flexibility to create more of the dishes you love.   This Panasonic microwave features flatbed cooking technology which eliminates the need for conventional turntables and increases the space inside the microwave cavity  Inverter cooking technology - the Panasonic 27L Convection Flatbed Microwave\'s graduated cooking options give you more control throughout the entire cooking process  Hassle-free cooking - the Panasonic 27L Convection Flatbed Microwave features an easy-to-read LCD screen  15 automatic cooking menus and a turbo defrost function  This microwave is fitted with a child lock function for peace of mind     What\'s in the Box:     1 x 27 Litre Convection Microwave  

              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://www.harveynormanpromotions.com.au/media/catalog/product//n/n/nncf770m_b.jpg',
            'cost_ex' => 602,
            'cost_freight' => 25,
            'cost_tax_percent' => 10,
            'cost_rrp' => 809.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',  
            'active' => 1,                         
        ]);
        $product11->tags()->attach(2);

        $product12 = Product::create([
            'name'                 => 'Breville - Juice Fountain Max Juicer - Stainless Steel',
            'supplier_id'                 =>  2,
           'model_number' => 'BJE410',
           'brand' => 'Breville',
           'category-string' => 'Juicers', 
           'category-string-check' => true,                              
            'description' => '
 There\'s just no comparison between freshly made fruit and vegetable juice and packaged juice from the store. But who has the time to chop and slice?  Breville revolutionised juicing in 1999 with the world\'s first juicer to juice whole fruit. Now  the chute is 25% larger to juice large apples  pears and oranges in seconds. And with its patented feed tube and filter  it extracts up to 20% more vitamins and minerals than other juicers (ref. National Measurement Institute  Australia (2003  2011). Automatic pulp ejection  two speeds  metal finish  and an easy clean design.  Minimal effort  maximum vitality.   1200 Watts  220 - 240 Volts   3 litre pulp container    DIshwasher safe parts    2 speeds with electronic controls    84mm - Large feed chute for whole fruit    1 litre juice container with froth separator    Safety lock mechanism prevents unsafe operation    Stainless steel cutting disc surrounded by Italian made micro mesh filter to extract up to 20% more vitamins and minerals than other juicers (ref. National Measurement Institute  Australia (2003  2011)      What\'s in the Box:     1 x Juice Fountain Max Juicer  

              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://www.harveynormanpromotions.com.au/media/catalog/product//b/j/bje410_breville_web.jpg',
            'cost_ex' => 178.48,
            'cost_freight' => 15,
            'cost_tax_percent' => 10,
            'cost_rrp' => 249.95,
            'mark_up_override' => false,
            'mark_up_integer' => 150,
            'mark_up_type' => 'dollar',  
            'active' => 1,                         
        ]);
        $product12->tags()->attach(2);

       $product13 = Product::create([
            'name'                 => 'Sangean - Portable Radio - Black',
            'supplier_id'                 =>  2,
           'model_number' => 'DPR45',
           'brand' => 'Sangean',
           'category-string' => 'Radios',  
           'category-string-check' => true,                            
            'description' => '
 This amazing DPR-45 Portable Receiver from Sangean is perfect for helping you rise in the morning and keeping you up to date with news and the latest music. It has a 3-Band tuner to cater for everyone\'s preferences and 45 station presets. You can also set your alarms with the large  easy-to-use controls.   The unique 3-band tuner allows you to have access to all radio stations as it covers FM  AM and DAB+. It has 45 preset stations (15 for each band) so you can easily flick between your favourite programs  With the large and easy-to-use controls you can program two alarms onto your DPR-45. You also have the option of specifying if the alarm recurs daily  on weekdays or weekends or if it\'s a one off     What\'s in the Box:     1 x Portable Radio  

              ',
            'primary_color' => 'green',
            'external_image_url' => 'http://www.harveynormanpromotions.com.au/media/catalog/product//d/p/dpr45_h.jpg',
            'cost_ex' => 164.78,
            'cost_freight' => 12,
            'cost_tax_percent' => 10,
            'cost_rrp' => 239.00,
            'mark_up_override' => false,
            'mark_up_integer' => 0,
            'mark_up_type' => 'percent',  
            'active' => 1,                         
        ]);
        $product13->tags()->attach(2);                                                                
    }
}