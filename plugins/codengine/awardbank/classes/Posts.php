<?php namespace Codengine\Awardbank\Classes;

use Codengine\Awardbank\Models\Post as MyPost;
use Codengine\Awardbank\Models\Team;
use Response;
use Illuminate\Routing\Controller;
use Auth;

class Posts extends Controller
{
    /**
     * Return List of posts.
     *
     * @return Response
     */
    public function all()
    {
        $posts = MyPost::get();
        return response()->json($posts);
    }

    public function postas()
    {
        $access = post('type');

        $user = Auth::getUser();

        if ($access == 'team'){
            $users = $user->teams->all();
        }else{
            $teams = $user->teams->all();
            $users = array();
            foreach ($teams as $key => $value) {
                foreach ($value->users as $key => $user) {
                    array_push($users, $user);
                }
            }
        }
        return response()->json($users);
    }

    public function postto()
    {
        $access = post('type');

        $user = Auth::getUser();
        //dump($user);
        
        if ($access == 'team'){
            $query = $user->teams->all();
        } elseif ($access == 'program') {
            $programid = $user->teams->first()->regions->first()->program_id;
            $query[] = $user->teams->first()->regions->first()->program->find($programid);
        }elseif ($access == 'region'){
            $query[] = $user->teams->first()->regions->first();
        }elseif ($access == 'organization'){
            $query[] = $user->teams->first()->regions->first()->program->first()->organization->first();
        }else{
            $teams = $user->teams->all();
            $query = array();
            foreach ($teams as $key => $value) {
                foreach ($value->users as $key => $user) {
                    array_push($query, $user);
                }
            }
        }
        return response()->json($query);
    }

    public function listproducts()
    {

        $user = Auth::getUser();

        $suppliers = $this->getSupplierByUserPermissions($user, post('permissions'), post('entities'), post('access'));

        foreach ($suppliers as $supp) {
            if ($supp->id == post('supp_id')){
                $hasAccess = true;
                break;
            }
        }

        if ($hasAccess){
            
        }

        return response()->json($users);
    }

    protected function getSupplierByUserPermissions($user,$entitypermissions,$entities,$access)
    {

        /**INSTATIATE PLACEHOLDER VARS**/

        $return = array();

        $permissions = null;

        /**CREATE BASE QUERY FOR ACCESS LEVEL FROM USER SESSION**/

        if($access == 'organization'){

            /**WILL NEED TO PASS A PROGRAM SESSION IN HERE IN PLACE OF FIRST**/

            $query = $user->teams->first()->regions->first()->program->first()->organization->first();

        } elseif ($access == 'program') {

            /**WILL NEED TO PASS A PROGRAM SESSION IN HERE IN PLACE OF FIRST**/
            $programid = $user->teams->first()->regions->first()->program_id;
            $query = $user->teams->first()->regions->first()->program->find($programid);

        } elseif ($access == 'region') {

            /**WILL NEED TO PASS A TEAMS SESSION ID IN HERE IN PLACE OF FIRST**/

            $query = $user->teams->first()->regions->first();

        } elseif ($access == 'team'){

            $query = $user->teams->first();

        } else {

             $query = $user;    
                    
        }

        /**PERMISSIONS FOR SUPPLIER**/

        if(in_array('suppliers',$entities)){

            $permissions = $query->permissions()->IsActive()->InEntity($entitypermissions)->has('suppliers')->orderBY('id', 'desc')->get();

            foreach($permissions as $permission){

                foreach($permission->suppliers as $supplier ){

                    if(!array_key_exists($supplier->id,$return)){

                        if($supplier != null){
                
                            $return[$supplier->id] = $supplier;     

                        }
                    }
                }
            }
        }

        return $return;

    }
}