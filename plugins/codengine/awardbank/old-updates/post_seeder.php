<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Post as Post;
use Codengine\Awardbank\Models\Permission as Permission;
use Rainlab\User\Models\User as User;
use Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {

        $post = Post::create([
            'title'             => 'Weekly Updates 9/10',
            'content'			=> 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'			=> null,
            'post_type'			=> 'post',
            'video_url'			=> 'http://localhost',
        ]);
        //$post->categories()->attach(31);
        $post->tags()->attach(6);
        $post->comment()->attach(1);
        $post->comment()->attach(2);
        $post->like()->attach(1);
        $post->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(5);    
        $post->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post->viewability()->attach($permission->id);

        $post2 = Post::create([
            'title'             => 'Program Minutes',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post2->categories()->attach(22);
        $post2->tags()->attach(4);
        $post2->comment()->attach(1);
        $post2->comment()->attach(2);
        $post2->like()->attach(1);
        $post2->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(29);    
        $post2->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post2->viewability()->attach($permission->id);


        $post3 = Post::create([
            'title'             => 'Tomorrow is cake day!',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post3->categories()->attach(22);
        $post3->tags()->attach(5);
        $post3->tags()->attach(2);        
        $post3->comment()->attach(1);
        $post3->comment()->attach(2);
        $post3->like()->attach(1);
        $post3->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(14);    
        $post3->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post3->viewability()->attach($permission->id);

        $post4 = Post::create([
            'title'             => 'End of quarter reports',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post4->categories()->attach(23);
        $post4->tags()->attach(4);
        $post4->comment()->attach(1);
        $post4->comment()->attach(2);
        $post4->like()->attach(1);
        $post4->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(33);    
        $post4->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post4->viewability()->attach($permission->id);

        $post5 = Post::create([
            'title'             => 'Reminder: Nominations for Staff Of The Year Closing soon',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post5->categories()->attach(21);
        $post5->tags()->attach(4);
        $post5->comment()->attach(1);
        $post5->comment()->attach(2);
        $post5->like()->attach(1);
        $post5->like()->attach(2);  
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(29); 
        $permission->users()->attach(13);  
        $permission->users()->attach(42);                       
        $post5->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post5->viewability()->attach($permission->id);

        $post6 = Post::create([
            'title'             => 'Lost Keys',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post6->categories()->attach(26);
        $post6->tags()->attach(1);
        $post6->comment()->attach(1);
        $post6->comment()->attach(2);
        $post6->like()->attach(1);
        $post6->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(8);    
        $post6->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post6->viewability()->attach($permission->id);

        $post7 = Post::create([
            'title'             => 'Best Work Display',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post7->categories()->attach(29);
        $post7->tags()->attach(4);
        $post7->comment()->attach(1);
        $post7->comment()->attach(2);
        $post7->like()->attach(1);
        $post7->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(37);    
        $post7->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post7->viewability()->attach($permission->id);

        $post8 = Post::create([
            'title'             => 'Presentation in the boardroom',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post8->categories()->attach(25);
        $post8->tags()->attach(3);
        $post8->comment()->attach(1);
        $post8->comment()->attach(2);
        $post8->like()->attach(1);
        $post8->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(3);    
        $permission->users()->attach(31);    
        $permission->users()->attach(29);                    
        $post8->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post8->viewability()->attach($permission->id);

        $post9 = Post::create([
            'title'             => 'Melbourne Office Shutdown Times',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post9->categories()->attach(22);
        $post9->tags()->attach(1);
        $post9->comment()->attach(1);
        $post9->comment()->attach(2);
        $post9->like()->attach(1);
        $post9->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(18);                      
        $post9->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post9->viewability()->attach($permission->id);

        $post10 = Post::create([
            'title'             => 'Weekly Birthdays!',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'post',
            'video_url'         => 'http://localhost',
        ]);
        //$post10->categories()->attach(30);
        $post10->tags()->attach(4);
        $post10->comment()->attach(1);
        $post10->comment()->attach(2);
        $post10->like()->attach(1);
        $post10->like()->attach(2);         
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(14);                      
        $post10->owners()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post10->viewability()->attach($permission->id);

        $post11 = Post::create([
            'title'             => 'October P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post11->categories()->attach(26);
        //$post11->tag()->attach(2);
        $post11->comment()->attach(1);
        $post11->comment()->attach(2);
        $post11->like()->attach(1);
        $post11->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->programs()->attach(2);                      
        $post11->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post11->viewability()->attach($permission->id);

        $post12 = Post::create([
            'title'             => 'September P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post12->categories()->attach(22);
        //$post12->tag()->attach(2);
        $post12->comment()->attach(1);
        $post12->comment()->attach(2);
        $post12->like()->attach(1);
        $post12->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->programs()->attach(2);                      
        $post12->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post12->viewability()->attach($permission->id);

        $post13 = Post::create([
            'title'             => 'August P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post13->categories()->attach(24);
        //$post13->tag()->attach(2);
        $post13->comment()->attach(1);
        $post13->comment()->attach(2);
        $post13->like()->attach(1);
        $post13->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->programs()->attach(2);                      
        $post13->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post13->viewability()->attach($permission->id);

        $post14 = Post::create([
            'title'             => 'June P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post14->categories()->attach(29);
        //$post14->tag()->attach(2);
        $post14->comment()->attach(1);
        $post14->comment()->attach(2);
        $post14->like()->attach(1);
        $post14->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(5);                      
        $post14->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post14->viewability()->attach($permission->id);

        $post15 = Post::create([
            'title'             => 'July P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post15->categories()->attach(24);
        //$post15->tag()->attach(2);
        $post15->comment()->attach(1);
        $post15->comment()->attach(2);
        $post15->like()->attach(1);
        $post15->like()->attach(2);  
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(5);                      
        $post15->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post15->viewability()->attach($permission->id);

        $post16 = Post::create([
            'title'             => 'May P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post16->categories()->attach(27);
        //$post16->tag()->attach(2);
        $post16->comment()->attach(1);
        $post16->comment()->attach(2);
        $post16->like()->attach(1);
        $post16->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(6);                      
        $post16->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post16->viewability()->attach($permission->id);

        $post17 = Post::create([
            'title'             => 'April P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post17->categories()->attach(21);
        //$post17->tag()->attach(2);
        $post17->comment()->attach(1);
        $post17->comment()->attach(2);
        $post17->like()->attach(1);
        $post17->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(6);                      
        $post17->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post17->viewability()->attach($permission->id);

        $post18 = Post::create([
            'title'             => 'March P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post18->categories()->attach(30);
        //$post18->tag()->attach(2);
        $post18->comment()->attach(1);
        $post18->comment()->attach(2);
        $post18->like()->attach(1);
        $post18->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(2);                      
        $post18->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post18->viewability()->attach($permission->id);

        $post19 = Post::create([
            'title'             => 'Feb P&A Tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post19->categories()->attach(28);
        //$post19->tag()->attach(2);
        $post19->comment()->attach(1);
        $post19->comment()->attach(2);
        $post19->like()->attach(1);
        $post19->like()->attach(2);
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(1);                      
        $post19->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post19->viewability()->attach($permission->id);


        $post20 = Post::create([
            'title'             => 'August P&A Jan',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'program-tool',
            'video_url'         => 'http://localhost',
        ]);
        //$post20->categories()->attach(22);
        //$post20->tag()->attach(2);
        $post20->comment()->attach(1);
        $post20->comment()->attach(2);
        $post20->like()->attach(1);
        $post20->like()->attach(2);    
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->regions()->attach(2);                      
        $post20->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(1);    
        $post20->viewability()->attach($permission->id);

        $post21 = Post::create([
            'title'             => 'Are you ok for Mondays Meeting?',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(18);                      
        $post21->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->users()->attach(11);                      
        $post21->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'direct';
        $permission->save();
        $permission->users()->attach(14);    
        $post21->viewability()->attach($permission->id);

        $post22 = Post::create([
            'title'             => 'Minutes 9/10',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(18);                      
        $post22->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->users()->attach(14);                      
        $post22->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'direct';
        $permission->save();
        $permission->users()->attach(8);    
        $post22->viewability()->attach($permission->id);

        $post23 = Post::create([
            'title'             => "What's for lunch?",
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(18);                      
        $post23->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->users()->attach(41);                      
        $post23->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'direct';
        $permission->save();
        $permission->users()->attach(30);    
        $post23->viewability()->attach($permission->id);

        $post24 = Post::create([
            'title'             => 'Meet @ Reception',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(18);                      
        $post24->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->users()->attach(20);                      
        $post24->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'direct';
        $permission->save();
        $permission->users()->attach(40);    
        $post24->viewability()->attach($permission->id);

        $post25 = Post::create([
            'title'             => 'Cat Photos',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(18);                      
        $post25->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->users()->attach(14);                      
        $post25->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'direct';
        $permission->save();
        $permission->users()->attach(8);    
        $post25->viewability()->attach($permission->id);


        $post26 = Post::create([
            'title'             => 'Competitor Rate Card',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(7);                      
        $post26->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->regions()->attach(5);    
        $post26->viewability()->attach($permission->id);


        $post27 = Post::create([
            'title'             => 'Did you read this mornings paper?',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(19);                      
        $post27->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->regions()->attach(6);    
        $post27->viewability()->attach($permission->id);

        $post28 = Post::create([
            'title'             => 'Quick Quiz',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(47);                      
        $post28->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->programs()->attach(2);                      
        $post28->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->programs()->attach(2);    
        $post28->viewability()->attach($permission->id);

        $post29 = Post::create([
            'title'             => 'Can you please upload the tools',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(1);                      
        $post29->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->programs()->attach(1);                      
        $post29->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->regions()->attach(1);    
        $post29->viewability()->attach($permission->id);

        $post30 = Post::create([
            'title'             => 'Friday Lunch',
            'content'           => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc eget elementum sem. Cras mattis imperdiet arcu ut volutpat. Donec ut felis eget sem fringilla iaculis ac quis ex. Donec enim nisl, sollicitudin quis nunc et, varius vestibulum lectus. Etiam quis laoreet nisi, in aliquet lorem. Donec sed est sollicitudin, imperdiet justo at, suscipit mauris.',
            'viewed_at'         => null,
            'post_type'         => 'message',
            'video_url'         => 'http://localhost',
        ]);
        $permission = new Permission;
        $permission->type = 'owner';
        $permission->save();
        $permission->users()->attach(3);                      
        $post30->owners()->attach($permission->id);        
        $permission = new Permission;
        $permission->type = 'alias';
        $permission->save();
        $permission->programs()->attach(1);                      
        $post30->alias()->attach($permission->id);
        $permission = new Permission;
        $permission->type = 'cascade';
        $permission->save();
        $permission->regions()->attach(2);    
        $post30->viewability()->attach($permission->id);

    }
}