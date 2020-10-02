<?php

ob_start();
define('API_KEY','1315559757:AAHc9RhvSxX5YrBh2hxBNNo-KzSizci9UCY');
echo "https://api.telegram.org/bot".API_KEY."/setwebhook?url=".$_SERVER['SERVER_NAME']."".$_SERVER['SCRIPT_NAME'];
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}



$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$chat_id = $message->chat->id;
$text = $message->text;
$chat_id2 = $update->callback_query->message->chat->id;
$message_id = $update->callback_query->message->message_id;
$data = $update->callback_query->data;
$from_id = $message->from->id;
$name = $update->message->from->first_name;
$ssa = json_decode(file_get_contents('data.json'),1);
	$status = bot('getChatMember',['chat_id'=>'@SS_SS','user_id'=>$from_id])->result->status;
        if($status == 'left'){
            bot('sendMessage',[
                'chat_id'=>$chat_id,
                'text'=>"- لا تستطيع بدء التحميل الا بعد الاشتراك بقناة البوت 🚫' @SS_SS",
                'reply_to_message_id'=>$message->message_id,
            ]);
      exit();
        }
if($text != null){
	if($text == '/start'){
		bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>'- مرحباً بك ، 
في بوت التحميل من اليوتيوب 🎤\'
 •  يمكنك من خلال البوت التحميل من اليوتيوب بثلاث صيغ مختلفه ومشاركتها مع اصدقائك عن طريق الانلاين 💬؛
🔍 ~» كما يمكنك البحث عن فيديو من خلال ارسال كلمه للبحث عن فيديوات مشابهه لها 

- @L6_L6',
				'reply_markup'=>json_encode([
		      'inline_keyboard'=>[
		      	[['text'=>"- اضغط هنا وتابع جديدنا 🇮🇶؛", 'url'=>"https://t.me/L6_L6"]],
		      ]
	      ])
		]);
	} elseif($text != (null and '/start')){
		if(preg_match('/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $text)){
			 preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $text, $match);
    	 $id = $match[1];
    	  bot('sendphoto',[
			      'chat_id'=>$chat_id,
        		'photo'=>$text,
        		'caption'=>"📌┇الان قم بأختيار احد الصيغ ؛",
            'reply_markup'=>json_encode([
               	'inline_keyboard'=>[
               [['text'=>'🎤┇ملف صوتي ؛','callback_data'=>"dl##$id"], ['text'=>'🎼┇تسجيل صوتي ؛','callback_data'=>"vo##$id"] ],[['text'=>'🎬┇فيديو ،','callback_data'=>"vi##$id"] ]
               ]
               ])]);
		} else {
			$item = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q=".urlencode($text)."&type=video&key=AIzaSyBdKFK59eajOVchi0TpuKkNGbYx4ZpiduI&maxResults=10"))->items;
	  	$keyboard["inline_keyboard"]=[];
	  	for ($i=0; $i < count($item); $i++) { 
	  		$keyboard["inline_keyboard"][$i] = [['text'=>$item[$i]->snippet->title,'callback_data'=>'dl##'.$item[$i]->id->videoId]];
	  	}
	  	$reply_markup=json_encode($keyboard);
	  	bot('sendMessage',[
	  		'chat_id'=>$chat_id,
	  		'text'=>'- قم بأختيار احد النتائج للتحميل \'💬',
	  		'reply_markup'=>$reply_markup
	  	]);
		}
	}
}
if($data != null){
	$yt = explode('##', $data);
		if($yt[0] == 'vi'){
		$get = json_decode(file_get_contents("https://wapis.ga/yt/?url=https://www.youtube.com/watch?v=".$yt[1]));
		$info = $get->result;
		$title = $info->info->title;
		$view_count = $info->info->view_count;
		$like_count = $info->info->like_count;
		$dislike_count = $info->info->dislike_count;
		$url = $get; 
	  bot('sendMessage',[
      'chat_id'=>$chat_id2,
      'text'=>'- انتظر قليلاً ⏱؛'
     ]);
     
     
     $size = $url->result->video[2]->sizebits;
     if($size > 50000000){
     	bot('sendMessage',[
     		'chat_id'=>$chat_id2,
     		'text'=>'🚫 ~> لا يمكن للبوت تنزيل الملفات الكبيرة '
     		]);
     
     //} else {
     	file_put_contents($chat_id2.'.mp4',file_get_contents( $url->result->video[2]->url ));
	     $video = bot('sendvideo',[
	       'chat_id'=>$chat_id2,
	       'video'=>new CURLFile($chat_id2.'.mp4'),
	       'reply_markup'=>json_encode([
	       		'inline_keyboard'=>[
		       			[['text'=>'👁 '.$view_count,'callback_data'=>'#']],
		       			[['text'=>'👍🏾 '.$like_count,'callback_data'=>'#'],['text'=>'👎🏾 '.$dislike_count,'callback_data'=>'#']],
		       			[['text'=>'• تحميل كـ ملف صوتي  🎬؛','callback_data'=>'dl##'.$yt[1]]],
		       			[['text'=>'💭 '.$info->info->comment_count,'callback_data'=>'#'],['text'=>'~ مشاركة 💬\'','switch_inline_query'=>'video#'.$yt[1]]],
		       			[['text'=>'- تحميل كـ تسجيل صوتي • 🎤','callback_data'=>'vo##'.$yt[1]]]
	       			]
	       	])
	 			]);
	 			$ssa['video']['video#'.$yt[1]] = ['title'=>$info->info->title,'file_id'=>$video->result->video->file_id,'like'=>$like_count,'dislike'=>$dislike_count,'view'=>$view_count];
	 			file_put_contents('data.json', json_encode($ssa));
     } 
      unlink($chat_id2.'.mp4');
	}
	if($yt[0] == 'vo'){
		$get = json_decode(file_get_contents("https://wapis.ga/yt/?url=https://www.youtube.com/watch?v=".$yt[1]));
		$info = $get->result;
		$title = $info->info->title;
		$view_count = $info->info->view_count;
		$like_count = $info->info->like_count;
		$dislike_count = $info->info->dislike_count;
		$url = $get; 
	  bot('sendMessage',[
      'chat_id'=>$chat_id2,
      'text'=>'- انتظر قليلاً ⏱؛'
     ]);
     
     $size = $url->result->video[2]->sizebits;
     if($size > 50000000){
     	bot('sendMessage',[
     		'chat_id'=>$chat_id2,
     		'text'=>'🚫 ~> لا يمكن للبوت تنزيل الملفات الكبيرة '
     		]);
     
     } else {
     file_put_contents($chat_id2.'.ogg',file_get_contents( $url->result->video[2]->url ));
     $voice = bot('sendvoice',[
       'chat_id'=>$chat_id2,
       'voice'=>new CURLFile($chat_id2.'.ogg'),
       'reply_markup'=>json_encode([
       		'inline_keyboard'=>[
	       			[['text'=>'👁 '.$view_count,'callback_data'=>'#']],
	       			[['text'=>'👍🏾 '.$like_count,'callback_data'=>'#'],['text'=>'👎🏾 '.$dislike_count,'callback_data'=>'#']],
	       			[['text'=>'• تحميل كـ ملف صوتي  🎬؛','callback_data'=>'dl##'.$yt[1]]],
	       			[['text'=>'💭 '.$info->info->comment_count,'callback_data'=>'#'],['text'=>'~ مشاركة 💬\'','switch_inline_query'=>'voice#'.$yt[1]]],
	       			[['text'=>'• تحميل كـ فيديو  🎬؛','callback_data'=>'vi##'.$yt[1]]],
       			]
       	])
 			]);
 			$ssa['voice']['voice#'.$yt[1]] = ['title'=>$info->info->title,'file_id'=>$voice->result->voice->file_id,'like'=>$like_count,'dislike'=>$dislike_count,'view'=>$view_count];
 			file_put_contents('data.json', json_encode($ssa));
     }
      unlink($chat_id2.'.ogg');
	}
	if($yt[0] == 'dl'){
		$get = json_decode(file_get_contents("https://wapis.ga/yt/?url=https://www.youtube.com/watch?v=".$yt[1]));
		$info = $get->result;
		$title = $info->info->title;
		$view_count = $info->info->view_count;
		$duration = $info->info->duration;
		$like_count = $info->info->like_count;
		$dislike_count = $info->info->dislike_count;
		$url = $get; 
	  bot('sendMessage',[
      'chat_id'=>$chat_id2,
      'text'=>'- انتظر قليلاً ⏱؛'
     ]);
     $du = explode(':', $url->result->info->duration);
     $duration = ((int)$du[0] * 60) + (int)$du[1]; 
     
     $size = $url->result->video[2]->sizebits;
     if($size > 50000000){
     	bot('sendMessage',[
     		'chat_id'=>$chat_id2,
     		'text'=>'🚫 ~> لا يمكن للبوت تنزيل الملفات الكبيرة '
     		]);
     
     } else {
     	file_put_contents($chat_id2.'.mp3',file_get_contents( $url->result->video[2]->url ));
     $audio = bot('sendaudio',[
       'chat_id'=>$chat_id2,
       'audio'=>new CURLFile($chat_id2.'.mp3'),
       'performer'=>$url->result->info->author,
       'title'=>$url->result->info->title,
       'duration'=>$duration ,
       'reply_markup'=>json_encode([
       		'inline_keyboard'=>[
	       			[['text'=>'👁 '.$view_count,'callback_data'=>'#']],
	       			[['text'=>'👍🏾 '.$like_count,'callback_data'=>'#'],['text'=>'👎🏾 '.$dislike_count,'callback_data'=>'#']],
	       			[['text'=>'• تحميل كـ فيديو  🎬؛','callback_data'=>'vi##'.$yt[1]]],
	       			[['text'=>'💭 '.$info->info->comment_count,'callback_data'=>'#'],['text'=>'~ مشاركة 💬\'','switch_inline_query'=>'audio#'.$yt[1]]],
	       			[['text'=>'- تحميل كـ تسجيل صوتي • 🎤','callback_data'=>'vo##'.$yt[1]]]
       			]
       	])
 			]);
 			$ssa['audio']['audio#'.$yt[1]] = ['file_id'=>$audio->result->audio->file_id,'like'=>$like_count,'dislike'=>$dislike_count,'view'=>$view_count];
 			file_put_contents('data.json', json_encode($ssa));
     }
      unlink($chat_id2.'.mp3');
	}

}
if($update->inline_query != null){
	$yt = explode('#', $update->inline_query->query);
	if($yt[0] == 'audio' and $ssa['audio'][$update->inline_query->query] != null){
		bot('answerInlineQuery',[
            'inline_query_id'=>$update->inline_query->id,    
            'results' =>json_encode([[
            	  'type'=>'audio',
            	  'audio_file_id'=>$ssa['audio'][$update->inline_query->query]['file_id'],
                'id'=>base64_encode(rand(5,555)),
                'reply_markup'=>[
                	'inline_keyboard'=>[
                		[['text'=>'👁 '.$ssa['audio'][$update->inline_query->query]['view'],'callback_data'=>'#']],
	       						[['text'=>'👍🏾 '.$ssa['audio'][$update->inline_query->query]['like'],'callback_data'=>'#'],['text'=>'👎🏾 '.$ssa['audio'][$update->inline_query->query]['dislike'],'callback_data'=>'#']],
                		]
                	]
            	]])
     ]);
	} elseif($yt[0] == 'video' and $ssa['video'][$update->inline_query->query] != null){
		bot('answerInlineQuery',[
            'inline_query_id'=>$update->inline_query->id,    
            'results' =>json_encode([[
            	  'type'=>'video',
            	  'title'=>$ssa['video'][$update->inline_query->query]['title'],
            	  'caption'=>$ssa['video'][$update->inline_query->query]['title'],
            	  'video_file_id'=>$ssa['video'][$update->inline_query->query]['file_id'],
                'id'=>base64_encode(rand(5,555)),
                'reply_markup'=>[
                	'inline_keyboard'=>[
                		[['text'=>'👁 '.$ssa['video'][$update->inline_query->query]['view'],'callback_data'=>'#']],
	       						[['text'=>'👍🏾 '.$ssa['video'][$update->inline_query->query]['like'],'callback_data'=>'#'],['text'=>'👎🏾 '.$ssa['video'][$update->inline_query->query]['dislike'],'callback_data'=>'#']],
                		]
                	]
            	]])
     ]);
	} elseif($yt[0] == 'voice' and $ssa['voice'][$update->inline_query->query] != null){
		bot('answerInlineQuery',[
            'inline_query_id'=>$update->inline_query->id,    
            'results' =>json_encode([[
            	  'type'=>'voice',
            	  'title'=>$ssa['voice'][$update->inline_query->query]['title'],
            	  'caption'=>$ssa['voice'][$update->inline_query->query]['title'],
            	  'voice_file_id'=>$ssa['voice'][$update->inline_query->query]['file_id'],
                'id'=>base64_encode(rand(5,555)),
                'reply_markup'=>[
                	'inline_keyboard'=>[
                		[['text'=>'👁 '.$ssa['voice'][$update->inline_query->query]['view'],'callback_data'=>'#']],
	       						[['text'=>'👍🏾 '.$ssa['voice'][$update->inline_query->query]['like'],'callback_data'=>'#'],['text'=>'👎🏾 '.$ssa['voice'][$update->inline_query->query]['dislike'],'callback_data'=>'#']],
                		]
                	]
            	]])
     ]);
	} else {
		$item = json_decode(file_get_contents("https://www.googleapis.com/youtube/v3/search?part=snippet&q=".urlencode($update->inline_query->query)."&type=video&key=AIzaSyBdKFK59eajOVchi0TpuKkNGbYx4ZpiduI&maxResults=10"))->items;
	  	$keyboard["inline_keyboard"]=[];
	  	 for($i=0;$i < count($item);$i++){
        $res[$i] = [
                'type'=>'article',
                'id'=>base64_encode(rand(5,555)),
                'thumb_url'=>$item[$i]->snippet->thumbnails->default->url,
                'title'=>$item[$i]->snippet->title,
                'input_message_content'=>['parse_mode'=>'HTML','message_text'=>"https://www.youtube.com/watch?v=".$item[$i]->id->videoId],
          ];
    }
	  	$r = json_encode($res);
    bot('answerInlineQuery',[
            'inline_query_id'=>$update->inline_query->id,    
            'results' =>($r)
        ]);
	}
}