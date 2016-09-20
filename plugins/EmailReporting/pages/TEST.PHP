<?php
	function build_reply($subject)
	{
		//pattern to slip off html head data
		$pattern = '\/<\!\[endif\]-->\\n<\/head>\/';
		//split initial email message body to split off html head data
		$subject = preg_split ( string ($pattern) , string ($subject) , -1 );
		
		//pattern to remove from message body
		$pattern = '\/<p class="MsoNormal"><o:p>&nbsp;<\/o:p><\/p>\/';
		//delete some MSO formatting
		$subject = preg_replace ( mixed ($pattern) , mixed ($replacement) , mixed ($subject[1]), -1 );
		$pattern = '\/<o:p><\/o:p>\/';
		//delete some MSO formatting
		//$subject = preg_replace ( mixed $pattern , mixed $replacement , mixed $subject, -1 );	
		
		//pattern to split messages in email body
		$pattern = '/<div style="border:none;border-top:solid #[0-9a-zA-Z]{6} 1.0pt;padding:3.0pt 0in 0in 0in">/';
		$replies[] = preg_split ( string ($pattern) , string ($subject), -1 );
		
		return $replies;
    };
//$pattern = '/<\!\[endif\]-->\n<\/head>/';
echo 'Test';
echo $pattern;
echo '<br>';
//$pattern = '/<p class="MsoNormal"><o:p>&nbsp;<\/o:p><\/p>/';
echo $pattern;
echo '<br>';
	?>