<?php
date_default_timezone_set('Asia/Baghdad');
if(!file_exists('config.json')){
	$token = readline('Enter Token: ');
	$id = readline('Enter Id: ');
	file_put_contents('config.json', json_encode(['id'=>$id,'token'=>$token]));
	
} else {
		  $config = json_decode(file_get_contents('config.json'),1);
	$token = $config['token'];
	$id = $config['id'];
}

if(!file_exists('accounts.json')){
    file_put_contents('accounts.json',json_encode([]));
}
include 'index.php';
try {
	$callback = function ($update, $bot) {
		global $id;
		if($update != null){
		  $config = json_decode(file_get_contents('config.json'),1);
		  $config['filter'] = $config['filter'] != null ? $config['filter'] : 1;
      $accounts = json_decode(file_get_contents('accounts.json'),1);
			if(isset($update->message)){
				$message = $update->message;
				$chatId = $message->chat->id;
				$text = $message->text;
				if($chatId == $id){
					if($text == '/start'){
              $bot->sendMessage([
                  'chat_id'=>$chatId,
                  'text'=>"𝐖𝐄𝐋𝐂𝐎𝐌𝐄 💓 
     𝐓𝐎 𝐘𝐎𝐔𝐑 𝐈𝐆 𝐁𝐔𝐒𝐒𝐈𝐍𝐄𝐒 𝐓𝐎𝐎𝐋 

 𝐁𝐘 ~ @VIPSCRAPER",
                  'reply_markup'=>json_encode([
                      'inline_keyboard'=>[
                          [['text'=>'إضافة حساب 🛃 ','callback_data'=>'login']],
                          [['text'=>'طرق الصيد ❇️','callback_data'=>'grabber']],
                          [['text'=>'بدء الصيد ✅','callback_data'=>'run'],['text'=>'ايقاف الصيد ❎','callback_data'=>'stop']],
                          [['text'=>'𝙎𝙏𝘼𝙏𝙐𝙎','callback_data'=>'status']]
                      ]
                  ])
              ]);   
          } elseif($text != null){
          	if($config['mode'] != null){
          		$mode = $config['mode'];
          		if($mode == 'addL'){
          			$ig = new ig(['file'=>'','account'=>['useragent'=>'Instagram 27.0.0.7.97 Android (23/6.0.1; 640dpi; 1440x2392; LGE/lge; RS988; h1; h1; en_US)']]);
          			list($user,$pass) = explode(':',$text);
          			list($headers,$body) = $ig->login($user,$pass);
          			// echo $body;
          			$body = json_decode($body);
          			if(isset($body->message)){
          				if($body->message == 'challenge_required'){
          					$bot->sendMessage([
          							'chat_id'=>$chatId,
          							'parse_mode'=>'markdown',
          							'text'=>"*Error*. \n - Challenge Required"
          					]);
          				} else {
          					$bot->sendMessage([
          							'chat_id'=>$chatId,
          							'parse_mode'=>'markdown',
          							'text'=>"*Error*.\n - Incorrect Username Or Password"
          					]);
          				}
          			} elseif(isset($body->logged_in_user)) {
          				$body = $body->logged_in_user;
          				preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $headers, $matches);
								  $CookieStr = "";
								  foreach($matches[1] as $item) {
								      $CookieStr .= $item."; ";
								  }
          				$account = ['cookies'=>$CookieStr,'useragent'=>'Instagram 27.0.0.7.97 Android (23/6.0.1; 640dpi; 1440x2392; LGE/lge; RS988; h1; h1; en_US)'];
          				
          				$accounts[$text] = $account;
          				file_put_contents('accounts.json', json_encode($accounts));
          				$mid = $config['mid'];
          				$bot->sendMessage([
          				      'parse_mode'=>'markdown',
          							'chat_id'=>$chatId,
          							'text'=>"*Done إضـافة حـساب جديـد 🖇 To Your Tool.*\n _Username_ : [$user])(instagram.com/$user)\n_Account Name_ : _{$body->full_name}_",
												'reply_to_message_id'=>$mid		
          					]);
          				$keyboard = ['inline_keyboard'=>[
										[['text'=>"إضـافة حـساب جديـد 🖇",'callback_data'=>'addL']]
									]];
		              foreach ($accounts as $account => $v) {
		                  $keyboard['inline_keyboard'][] = [['text'=>$account,'callback_data'=>'ddd'],['text'=>"تـسجيل الخـروج  🔚",'callback_data'=>'del&'.$account]];
		              }
		              $keyboard['inline_keyboard'][] = [['text'=>'الصفحـة الرئـيسية','callback_data'=>'back']];
		              $bot->editMessageText([
		                  'chat_id'=>$chatId,
		                  'message_id'=>$mid,
		                  'text'=>"صفحـة التــحكم فـــي الحســابــات 📟.",
		                  'reply_markup'=>json_encode($keyboard)
		              ]);
		              $config['mode'] = null;
		              $config['mid'] = null;
		              file_put_contents('config.json', json_encode($config));
          			}
          		}  elseif($mode == 'selectFollowers'){
          		  if(is_numeric($text)){
          		    bot('sendMessage',[
          		        'chat_id'=>$chatId,
          		        'text'=>"تم التعديل.",
          		        'reply_to_message_id'=>$config['mid']
          		    ]);
          		    $config['filter'] = $text;
          		    $bot->editMessageText([
                      'chat_id'=>$chatId,
                      'message_id'=>$mid,
                      'text'=>"𝐖𝐄𝐋𝐂𝐎𝐌𝐄 💓 
     𝐓𝐎 𝐘𝐎𝐔𝐑 𝐈𝐆 𝐁𝐔𝐒𝐒𝐈𝐍𝐄𝐒 𝐓𝐎𝐎𝐋 

 𝐁𝐘 ~ @VIPSCRAPER",
                  'reply_markup'=>json_encode([
                      'inline_keyboard'=>[
                          [['text'=>'إضافة حساب 🛃 ','callback_data'=>'login']],
                          [['text'=>'طرق الصيد ❇️','callback_data'=>'grabber']],
                          [['text'=>'بدء الصيد ✅','callback_data'=>'run'],['text'=>'ايقاف الصيد ❎','callback_data'=>'stop']],
                          [['text'=>'𝙎𝙏𝘼𝙏𝙐𝙎','callback_data'=>'status']]
                      ]
                  ])
                  ]);
          		    $config['mode'] = null;
		              $config['mid'] = null;
		              file_put_contents('config.json', json_encode($config));
          		  } else {
          		    bot('sendMessage',[
          		        'chat_id'=>$chatId,
          		        'text'=>'- يرجى ارسال رقم فقط .'
          		    ]);
          		  }
          		} else {
          		  switch($config['mode']){
          		    case 'search': 
          		      $config['mode'] = null; 
          		      $config['words'] = $text;
          		      file_put_contents('config.json', json_encode($config));
          		      exec('screen -dmS gr php search.php');
          		      break;
          		      case 'followers': 
          		      $config['mode'] = null; 
          		      $config['words'] = $text;
          		      file_put_contents('config.json', json_encode($config));
          		      exec('screen -dmS gr php followers.php');
          		      break;
          		      case 'following': 
          		      $config['mode'] = null; 
          		      $config['words'] = $text;
          		      file_put_contents('config.json', json_encode($config));
          		      exec('screen -dmS gr php following.php');
          		      break;
          		      case 'hashtag': 
          		      $config['mode'] = null; 
          		      $config['words'] = $text;
          		      file_put_contents('config.json', json_encode($config));
          		      exec('screen -dmS gr php hashtag.php');
          		      break;
          		  }
          		}
          	}
          }
				} else {
					$bot->sendMessage([
							'chat_id'=>$chatId,
							'text'=>"𝗛𝗘𝗟𝗟𝗢 !
~ اسـعـار الاشتراك 🧿
~» يـوم : 1$💸
~» اسبـوعـي : 5$ 💸
~ 15 يـوم : 8$ 💸
~ طـرق الـدفـع 💰
﴿ اورنج~ انوي ~بايـبـال ~ قـوقـل بـلاي ﴾
ـــــــــــــــــــــــــــــــــــــــــــــــــــ
=>مـلـفات البـوت صـيـد ~» 15$ 
=>احـدثـلـك مـلـفاتـك البـوت صـيـد ~ 5$
=>معـندي تيـست او تجـربه فقـط اشـتـراك ✅
َ
𝙏𝙊 𝘽𝙐𝙔 💲⤵️",
							'reply_markup'=>json_encode([
                  'inline_keyboard'=>[
                      [['text'=>'𝘿𝙀𝙑𝙀𝙇𝙊𝙋𝙀𝙍 ❤💸','url'=>'t.me/RKRKQ']]
                  ]
							])
					]);
				}
			} elseif(isset($update->callback_query)) {
          $chatId = $update->callback_query->message->chat->id;
          $mid = $update->callback_query->message->message_id;
          $data = $update->callback_query->data;
          echo $data;
          if($data == 'login'){
              
        		$keyboard = ['inline_keyboard'=>[
										[['text'=>"إضـافة حـساب جديـد 🖇",'callback_data'=>'addL']]
									]];
		              foreach ($accounts as $account => $v) {
		                  $keyboard['inline_keyboard'][] = [['text'=>$account,'callback_data'=>'ddd'],['text'=>"تـسجيل الخـروج  🔚",'callback_data'=>'del&'.$account]];
		              }
		              $keyboard['inline_keyboard'][] = [['text'=>'الصفحـة الرئـيسية','callback_data'=>'back']];
		              $bot->editMessageText([
		                  'chat_id'=>$chatId,
		                  'message_id'=>$mid,
		                  'text'=>"صفحـة التــحكم فـــي الحســابــات 📟.",
		                  'reply_markup'=>json_encode($keyboard)
		              ]);
          } elseif($data == 'addL'){
          	
          	$config['mode'] = 'addL';
          	$config['mid'] = $mid;
          	file_put_contents('config.json', json_encode($config));
          	$bot->sendMessage([
          			'chat_id'=>$chatId,
          			'text'=>"Send Account Like : \n `user:pass`",
          			'parse_mode'=>'markdown'
          	]);
          } elseif($data == 'grabber'){
            
            $for = $config['for'] != null ? $config['for'] : 'حدد الحساب';
            $count = count(explode("\n", file_get_contents($for)));
            $bot->editMessageText([
                'chat_id'=>$chatId,
                'message_id'=>$mid,
                'text'=>"Users collection page. \n - Users : $count \n - For Account : $for",
                'reply_markup'=>json_encode([
                    'inline_keyboard'=>[
                        [['text'=>'من البحث 🔎','callback_data'=>'search']],
                        [['text'=>'من هشتاك #⃣','callback_data'=>'hashtag'],['text'=>'من الاكسبلور 💡','callback_data'=>'explore']],
                        [['text'=>'𝐅𝐎𝐋𝐋𝐎𝐖𝐄𝐑𝐒','callback_data'=>'followers'],['text'=>"𝐅𝐎𝐋𝐋𝐎𝐖𝐈𝐍𝐆",'callback_data'=>'following']],
                        [['text'=>"الحساب المحدد 💌: $for",'callback_data'=>'for']],
                        [['text'=>'لسته جديدة ⚠️','callback_data'=>'newList'],['text'=>'لسته قديمة ⚠️','callback_data'=>'append']],
                        [['text'=>'الصفحة الرئيسية ♻️','callback_data'=>'back']]
                    ]
                ])
            ]);
          } elseif($data == 'search'){
            $bot->sendMessage([
                'chat_id'=>$chatId,
                'text'=>"Now Send Words to search \n - You can send more than one word by putting a space between them"
            ]);
            $config['mode'] = 'search';
            file_put_contents('config.json', json_encode($config));
          } elseif($data == 'followers'){
            $bot->sendMessage([
                'chat_id'=>$chatId,
                'text'=>"Now Send Users to check out followers \n - You can send more than one user by putting a space between them"
            ]);
            $config['mode'] = 'followers';
            file_put_contents('config.json', json_encode($config));
          } elseif($data == 'following'){
            $bot->sendMessage([
                'chat_id'=>$chatId,
                'text'=>"Now Send Users to check out following \n - You can send more than one user by putting a space between them"
            ]);
            $config['mode'] = 'following';
            file_put_contents('config.json', json_encode($config));
          } elseif($data == 'hashtag'){
            $bot->sendMessage([
                'chat_id'=>$chatId,
                'text'=>"Now Send HashTag without # \n 0 You can only send one."
            ]);
            $config['mode'] = 'hashtag';
            file_put_contents('config.json', json_encode($config));
          } elseif($data == 'newList'){
            file_put_contents('a','new');
            $bot->answerCallbackQuery([
							'callback_query_id'=>$update->callback_query->id,
							'text'=>"تــم تحــديد قــائمــة جــديــدة.",
							'show_alert'=>1
						]);
          } elseif($data == 'append'){ 
            file_put_contents('a', 'ap');
            $bot->answerCallbackQuery([
							'callback_query_id'=>$update->callback_query->id,
							'text'=>"تــم تحــديد قــائمــة المــوجــودة.",
							'show_alert'=>1
						]);
						
          } elseif($data == 'for'){
            if(!empty($accounts)){
            $keyboard = [];
             foreach ($accounts as $account => $v) {
                $keyboard['inline_keyboard'][] = [['text'=>$account,'callback_data'=>'forg&'.$account]];
              }
              $bot->editMessageText([
                  'chat_id'=>$chatId,
                  'message_id'=>$mid,
                  'text'=>"حـــدد الحســاب 📟.",
                  'reply_markup'=>json_encode($keyboard)
              ]);
            } else {
              $bot->answerCallbackQuery([
							'callback_query_id'=>$update->callback_query->id,
							'text'=>"Add Account First.",
							'show_alert'=>1
						]);
            }
          } elseif($data == 'selectFollowers'){
            bot('sendMessage',[
                'chat_id'=>$chatId,
                'text'=>'قم بأرسال عدد متابعين .'  
            ]);
            $config['mode'] = 'selectFollowers';
          	$config['mid'] = $mid;
          	file_put_contents('config.json', json_encode($config));
          } elseif($data == 'run'){
            if(!empty($accounts)){
            $keyboard = [];
             foreach ($accounts as $account => $v) {
                $keyboard['inline_keyboard'][] = [['text'=>$account,'callback_data'=>'start&'.$account]];
              }
              $bot->editMessageText([
                  'chat_id'=>$chatId,
                  'message_id'=>$mid,
                  'text'=>"حـــدد الحســاب 📟.",
                  'reply_markup'=>json_encode($keyboard)
              ]);
            } else {
              $bot->answerCallbackQuery([
							'callback_query_id'=>$update->callback_query->id,
							'text'=>"Add Account First.",
							'show_alert'=>1
						]);
            }
          }elseif($data == 'stop'){
            if(!empty($accounts)){
            $keyboard = [];
             foreach ($accounts as $account => $v) {
                $keyboard['inline_keyboard'][] = [['text'=>$account,'callback_data'=>'stop&'.$account]];
              }
              $bot->editMessageText([
                  'chat_id'=>$chatId,
                  'message_id'=>$mid,
                  'text'=>"حـــدد الحســاب 📟.",
                  'reply_markup'=>json_encode($keyboard)
              ]);
            } else {
              $bot->answerCallbackQuery([
							'callback_query_id'=>$update->callback_query->id,
							'text'=>"Add Account First.",
							'show_alert'=>1
						]);
            }
          }elseif($data == 'stopgr'){
            shell_exec('screen -S gr -X quit');
            $bot->answerCallbackQuery([
							'callback_query_id'=>$update->callback_query->id,
							'text'=>"Done Stop Collecting.",
						// 	'show_alert'=>1
						]);
						$for = $config['for'] != null ? $config['for'] : 'حـــدد الحســاب 📟';
            $count = count(explode("\n", file_get_contents($for)));
						$bot->editMessageText([
                'chat_id'=>$chatId,
                'message_id'=>$mid,
                'text'=>"Users collection page. \n - Users : $count \n - For Account : $for",
                'reply_markup'=>json_encode([
                    'inline_keyboard'=>[
                        [['text'=>'من البحث 🔎','callback_data'=>'search']],
                        [['text'=>'من هشتاك #⃣','callback_data'=>'hashtag'],['text'=>'من الاكسبلور 💡','callback_data'=>'explore']],
                        [['text'=>'𝐅𝐎𝐋𝐋𝐎𝐖𝐄𝐑𝐒','callback_data'=>'followers'],['text'=>"𝐅𝐎𝐋𝐋𝐎𝐖𝐈𝐍𝐆",'callback_data'=>'following']],
                        [['text'=>"الحساب المحدد 💌: $for",'callback_data'=>'for']],
                        [['text'=>'لسته جديدة ⚠️','callback_data'=>'newList'],['text'=>'لسته قديمة ⚠️','callback_data'=>'append']],
                        [['text'=>'الصفحة الرئيسية ♻️','callback_data'=>'back']]
                    ]
                ])
            ]);
          } elseif($data == 'explore'){
            exec('screen -dmS gr php explore.php');
          } elseif($data == 'status'){
					$status = '';
					foreach($accounts as $account => $ac){
						$c = explode(':', $account)[0];
						$x = exec('screen -S '.$c.' -Q select . ; echo $?');
						if($x == '0'){
				        $status .= "*$account* ~> _Working_\n";
				    } else {
				        $status .= "*$account* ~> _Stop_\n";
				    }
					}
					$bot->sendMessage([
							'chat_id'=>$chatId,
							'text'=>"Accounts Status: \n\n $status",
							'parse_mode'=>'markdown'
						]);
				} elseif($data == 'back'){
          	$bot->editMessageText([
                      'chat_id'=>$chatId,
                      'message_id'=>$mid,
                      'text'=>"𝐖𝐄𝐋𝐂𝐎𝐌𝐄 💓 
     𝐓𝐎 𝐘𝐎𝐔𝐑 𝐈𝐆 𝐁𝐔𝐒𝐒𝐈𝐍𝐄𝐒 𝐓𝐎𝐎𝐋 

 𝐁𝐘 ~ @VIPSCRAPER",
                  'reply_markup'=>json_encode([
                      'inline_keyboard'=>[
                          [['text'=>'إضافة حساب 🛃 ','callback_data'=>'login']],
                          [['text'=>'طرق الصيد ❇️','callback_data'=>'grabber']],
                          [['text'=>'بدء الصيد ✅','callback_data'=>'run'],['text'=>'ايقاف الصيد ❎','callback_data'=>'stop']],
                          [['text'=>'𝙎𝙏𝘼𝙏𝙐𝙎','callback_data'=>'status']]
                      ]
                  ])
                  ]);
          } else {
          	$data = explode('&',$data);
          	if($data[0] == 'del'){
          		
          		unset($accounts[$data[1]]);
          		file_put_contents('accounts.json', json_encode($accounts));
              $keyboard = ['inline_keyboard'=>[
							[['text'=>"إضـافة حـساب جديـد 🖇",'callback_data'=>'addL']]
									]];
		              foreach ($accounts as $account => $v) {
		                  $keyboard['inline_keyboard'][] = [['text'=>$account,'callback_data'=>'ddd'],['text'=>"تـسجيل الخـروج  🔚",'callback_data'=>'del&'.$account]];
		              }
		              $keyboard['inline_keyboard'][] = [['text'=>'الصفحـة الرئـيسية','callback_data'=>'back']];
		              $bot->editMessageText([
		                  'chat_id'=>$chatId,
		                  'message_id'=>$mid,
		                  'text'=>"صفحـة التــحكم فـــي الحســابــات 📟.",
		                  'reply_markup'=>json_encode($keyboard)
		              ]);
          	} elseif($data[0] == 'forg'){
          	  $config['for'] = $data[1];
          	  file_put_contents('config.json',json_encode($config));
              $for = $config['for'] != null ? $config['for'] : 'Select';
              $count = count(explode("\n", file_get_contents($for)));
              $bot->editMessageText([
                'chat_id'=>$chatId,
                'message_id'=>$mid,
                'text'=>"Users collection page. \n - Users : $count \n - For Account : $for",
                'reply_markup'=>json_encode([
                    'inline_keyboard'=>[
                        [['text'=>'من البحث 🔎','callback_data'=>'search']],
                        [['text'=>'من هشتاك #⃣','callback_data'=>'hashtag'],['text'=>'من الاكسبلور 💡','callback_data'=>'explore']],
                        [['text'=>'𝐅𝐎𝐋𝐋𝐎𝐖𝐄𝐑𝐒','callback_data'=>'followers'],['text'=>"𝐅𝐎𝐋𝐋𝐎𝐖𝐈𝐍𝐆",'callback_data'=>'following']],
                        [['text'=>"الحساب المحدد 💌: $for",'callback_data'=>'for']],
                        [['text'=>'لسته جديدة ⚠️','callback_data'=>'newList'],['text'=>'لسته قديمة ⚠️','callback_data'=>'append']],
                        [['text'=>'الصفحة الرئيسية ♻️','callback_data'=>'back']]
                    ]
                ])
            ]);
          	} elseif($data[0] == 'start'){
          	  file_put_contents('screen', $data[1]);
          	  $bot->editMessageText([
                      'chat_id'=>$chatId,
                      'message_id'=>$mid,
                      'text'=>"𝐖𝐄𝐋𝐂𝐎𝐌𝐄 💓 
     𝐓𝐎 𝐘𝐎𝐔𝐑 𝐈𝐆 𝐁𝐔𝐒𝐒𝐈𝐍𝐄𝐒 𝐓𝐎𝐎𝐋 

 𝐁𝐘 ~ @VIPSCRAPER",
                  'reply_markup'=>json_encode([
                      'inline_keyboard'=>[
                          [['text'=>'إضافة حساب 🛃 ','callback_data'=>'login']],
                          [['text'=>'طرق الصيد ❇️','callback_data'=>'grabber']],
                          [['text'=>'بدء الصيد ✅','callback_data'=>'run'],['text'=>'ايقاف الصيد ❎','callback_data'=>'stop']],
                          [['text'=>'𝙎𝙏𝘼𝙏𝙐𝙎','callback_data'=>'status']]
                      ]
                  ])
                  ]);
              exec('screen -dmS '.explode(':',$data[1])[0].' php start.php');
              $bot->sendMessage([
                'chat_id'=>$chatId,
                'text'=>"*جــاري الفـحص برعـاية『𝐓𝐀𝐍𝐆𝐄𝐑𝐀』𖤍᭄༒*\n Account: `".explode(':',$data[1])[0].'`',
                'parse_mode'=>'markdown'
              ]);
          	} elseif($data[0] == 'stop'){
          	  $bot->editMessageText([
                      'chat_id'=>$chatId,
                      'message_id'=>$mid,
                      'text'=>"𝐖𝐄𝐋𝐂𝐎𝐌𝐄 💓 
     𝐓𝐎 𝐘𝐎𝐔𝐑 𝐈𝐆 𝐁𝐔𝐒𝐒𝐈𝐍𝐄𝐒 𝐓𝐎𝐎𝐋 

 𝐁𝐘 ~ @VIPSCRAPER",
                      'reply_markup'=>json_encode([
                      'inline_keyboard'=>[
                          [['text'=>'إضافة حساب 🛃 ','callback_data'=>'login']],
                          [['text'=>'طرق الصيد ❇️','callback_data'=>'grabber']],
                          [['text'=>'بدء الصيد ✅','callback_data'=>'run'],['text'=>'ايقاف الصيد ❎','callback_data'=>'stop']],
                          [['text'=>'𝙎𝙏𝘼𝙏𝙐𝙎','callback_data'=>'status']]
                      ]
                    ])
                  ]);
              exec('screen -S '.explode(':',$data[1])[0].' -X quit');
          	}
          }
			}
		}
	};
	$bot = new EzTG(array('throw_telegram_errors'=>false,'token' => $token, 'callback' => $callback));
} catch(Exception $e){
	echo $e->getMessage().PHP_EOL;
	sleep(1);
}