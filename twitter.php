<?php
    /**************************************************

		GET���\�b�h�̃��N�G�X�g [�A�N�Z�X�g�[�N��]

	**************************************************/

	// �ݒ�
	$api_key = "UhuBsj54DbRnnHGQYaPywHmAi";
	$api_secret = "e1zSaGoQorVKzyW5W7U8LaAmkUsf3nnjdIHCxh45Y03bnnBj86";
	$access_token = "42566916-5CI2BDtmiUQdWFKtTY6ajgxmLlYjURI4ftW2K88h3";
	$access_token_secret = "YHfdFB1RJxIcHQ6XOriX0GZhmZmFpNmI5wegaBIRDflIU";
	
	$request_url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	$request_method = 'GET';

	// �p�����[�^
	$params_a = array(
		'screen_name' => '@realDonaldTrump' ,
		'count' => 10 ,
		'tweet_mode' => 'extended'
	) ;

	// �L�[���쐬���� (URL�G���R�[�h����)
	$signature_key = rawurlencode($api_secret) . '&' . rawurlencode($access_token_secret);

	// �p�����[�^�����p
	$params_b = array(
		'oauth_token' => $access_token,
		'oauth_consumer_key' => $api_key,
		'oauth_signature_method' => 'HMAC-SHA1',
		'oauth_timestamp' => time(),
		'oauth_nonce' => microtime(),
		'oauth_version' => '1.0',
	);

	// �p�����[�^�}�[�W
	$params_c = array_merge( $params_a , $params_b ) ;

	// �A�z�z����A���t�@�x�b�g���ɕ��ёւ���
	ksort($params_c);

	// �p�����[�^�̘A�z�z���[�L�[=�l&�L�[=�l...]�̕�����ɕϊ�����
	$request_params = http_build_query($params_c, '', '&');

	// �ꕔ�̕�������t�H���[
	$request_params = str_replace(array('+', '%7E'), array('%20', '~' ), $request_params);

	// �ϊ������������URL�G���R�[�h
	$request_params = rawurlencode($request_params);

	// ���N�G�X�g���\�b�h��URL�G���R�[�h
	$encoded_request_method = rawurlencode($request_method);
	 
	// ���N�G�X�gURL��URL�G���R�[�h����
	$encoded_request_url = rawurlencode($request_url);
	 
	// ���N�G�X�g���\�b�h�A���N�G�X�gURL�A�p�����[�^��[&]�Ōq��
	$signature_data = $encoded_request_method . '&' . $encoded_request_url . '&' . $request_params;

	// �L�[[$signature_key]�ƃf�[�^[$signature_data]�𗘗p���āAHMAC-SHA1�����̃n�b�V���l�ɕϊ�����
	$hash = hash_hmac('sha1', $signature_data, $signature_key, TRUE);

	// base64�G���R�[�h���āA����[$signature]����������
	$signature = base64_encode($hash);

	// �p�����[�^�̘A�z�z��A[$params]�ɁA�쐬����������������
	$params_c['oauth_signature'] = $signature;

	// �p�����[�^�̘A�z�z���[�L�[=�l,�L�[=�l,...]�̕�����ɕϊ�����
	$header_params = http_build_query($params_c, '', ',');

	// ���N�G�X�g�p�̃R���e�L�X�g
	$context = array(
		'http' => array(
			'method' => $request_method,
			'header' => array(
				'Authorization: OAuth ' . $header_params ,
			),
		),
	);

	// �p�����[�^������ꍇ�AURL�̖����ɒǉ�
	if( $params_a ) {
		$request_url .= '?' . http_build_query($params_a);
	}

	// cURL���g���ă��N�G�X�g
	$curl = curl_init() ;
	curl_setopt($curl, CURLOPT_URL, $request_url);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $context['http']['method']);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $context['http']['header']);
	curl_setopt($curl, CURLOPT_TIMEOUT, 5);
	$res1 = curl_exec( $curl ) ;
	$res2 = curl_getinfo( $curl ) ;
	curl_close( $curl ) ;

	// �擾�����f�[�^
	$json = substr( $res1, $res2['header_size'] );
	$header = substr($res1, 0, $res2['header_size']);

	// ������full_text��\��
	$tweets = json_decode($json, true);
	foreach ($tweets as $tweet) {
	  $text = '<<< ' . date('Y/m/d H:i:s', strtotime($tweet['created_at'])) . " >>>\n" . $tweet['full_text'] . "\n\n";
	  echo $text;
	}
?>