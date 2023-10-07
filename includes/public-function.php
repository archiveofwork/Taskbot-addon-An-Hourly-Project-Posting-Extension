<?php

/**
 * List project type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_type')){
	function taskbot_hourly_project_type($lists=array()) {
		
		$lists['hourly']  = array(
                    'title'     => esc_html__('Hourly','taskbot-hourly-addon'),
                    'details'   => esc_html__('Pay each freelancer on hourly rate','taskbot-hourly-addon'),
                    'icon'      => 'icon-file-text tk-purple-icon'
            );
        return $lists;
	}
    add_filter( 'taskbot_filter_project_type', 'taskbot_hourly_project_type' );
}

/**
 * Requried field for job creation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_validation_step2')){
	function taskbot_hourly_project_validation_step2($required_fields=array(),$data=array()) {
        $project_type   = !empty($data['project_type']) ? $data['project_type'] : '';
        if( !empty($project_type) && $project_type === 'hourly'){
            $required_fields['min_hourly_price']    = esc_html__( 'Minimum hourly price is required', 'taskbot-hourly-addon' );
            $required_fields['max_hourly_price']    = esc_html__( 'Maximum hourly price is required', 'taskbot-hourly-addon' );
            $required_fields['payment_mode']        = esc_html__( 'Please select payment mode', 'taskbot-hourly-addon' );
            $required_fields['max_hours']           = esc_html__( 'Maximum hours field is required', 'taskbot-hourly-addon' );
        }
        return $required_fields;
	}
    add_filter( 'taskbot_project_validation_step2', 'taskbot_hourly_project_validation_step2',10,2 );
}

/**
 * Save hourly project step 2
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_save_project_step2')){
	function taskbot_save_project_step2($project_id=0,$data=array()) {
        if( !empty($project_id) && !empty($data['project_type']) && $data['project_type'] === 'hourly' ){
            $min_price   = !empty($data['min_hourly_price']) ? sanitize_text_field($data['min_hourly_price']) : 0;
            $max_price   = !empty($data['max_hourly_price']) ? sanitize_text_field($data['max_hourly_price']) : 0;
            $max_hours          = !empty($data['max_hours']) ? sanitize_text_field($data['max_hours']) : 0;
            $payment_mode       = !empty($data['payment_mode']) ? sanitize_text_field($data['payment_mode']) : 0;

            $project_meta       = get_post_meta( $project_id, 'tb_project_meta',true );
            $project_meta       = !empty($project_meta) ? $project_meta : array();

            $project_meta['max_hours']              = $max_hours;
            $project_meta['payment_mode']           = $payment_mode;
            $project_meta['max_price']       = $max_price;
            $project_meta['min_price']       = $min_price;
            
            update_post_meta( $project_id, 'payment_mode',$payment_mode );
            update_post_meta( $project_id, 'max_hours',$max_hours );

            update_post_meta( $project_id, 'tb_project_meta',$project_meta );
            update_post_meta( $project_id, 'min_price',$min_price );
            update_post_meta( $project_id, 'max_price',$max_price );
        }
	}
    add_action('taskbot_save_project_step2', 'taskbot_save_project_step2',10,2);
}

/**
 * Horly price text
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_type_text')){
	function taskbot_hourly_project_type_text($project_type='') {
		$text   = '';
		if( !empty($project_type) && $project_type === 'hourly'){
            $text   =  esc_html__('Hourly price project','taskbot-hourly-addon');
        }
        return $text;
	}
    add_filter( 'taskbot_filter_project_type_text', 'taskbot_hourly_project_type_text' );
}

/**
 * Horly price class
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_type_class')){
	function taskbot_hourly_project_type_class($project_type='') {
		$class  = '';
		if( !empty($project_type) && $project_type === 'hourly'){
            $class  = 'tk-success-tag';
        }
        return $class;
	}
    add_filter( 'taskbot_filter_project_type_class', 'taskbot_hourly_project_type_class' );
}

/**
 * duplicate project keys
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_filter_duplicat_project_keys')){
	function taskbot_filter_duplicat_project_keys($project_id=0,$data=array()) {
        $project_type   = get_post_meta( $post_id, 'project_type',true );
        $project_type   = !empty($project_type) ? $project_type : '';

        if( !empty($project_type) && $project_type === 'hourly'){
            $data['min_price'];
            $data['max_price'];
            $data['payment_mode'];
            $data['max_hours'];
            $data['max_hours'];
        }

        return $data;
	}
    add_filter( 'taskbot_duplicate_job_key_filter', 'taskbot_filter_duplicat_project_keys',10,2 );
}

/**
 * project price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_project_price_text_fitler')){
	function taskbot_project_price_text_fitler($post_id=0) {
        $project_meta       = get_post_meta( $post_id, 'tb_project_meta', true);
        $project_type       = !empty($project_meta['project_type']) ? $project_meta['project_type'] : '';
        
        if( !empty($project_type) && $project_type === 'hourly'){
            $min_price      = !empty($project_meta['min_price']) ? $project_meta['min_price'] : 0;
            $max_price      = !empty($project_meta['max_price']) ? $project_meta['max_price'] : 0;
            $project_price  = taskbot_price_format($min_price,'return').'-'.taskbot_price_format($max_price,'return');
            $project_price  = sprintf(__('%s/hr','taskbot-hourly-addon'),$project_price);
            return $project_price;
        }
        
	}
    add_filter( 'taskbot_project_price_text', 'taskbot_project_price_text_fitler');
}

/**
 * Proposal price
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_proposal_listing_price')){
	function taskbot_proposal_listing_price($proposal_id=0) {
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array();
        $price              = isset($proposal_meta['price']) ? taskbot_price_format($proposal_meta['price'],'return') : 0;

        ob_start();
        echo sprintf(__('%s/hr','taskbot-hourly-addon'),$price);
        echo ob_get_clean();
	}
    add_action('taskbot_proposal_listing_price', 'taskbot_proposal_listing_price');
}

/**
 * Get time difference
 *
 * @throws error
 * @return 
 */
if ( ! function_exists( 'taskbot_cal_hours' ) ) {
    function taskbot_cal_hours( $start_hours='',$end_hours='') {
		$starttime 		= strtotime($start_hours);
		$endtime 		= strtotime($end_hours);
		$difference = round(abs($endtime - $starttime) / 3600,2);
		return $difference;
	}
}

/**
 * Get Difference between dates
 *
 * @throws error
 * @return 
 */
if (!function_exists('taskbot_get_diff_dates')) {
	function taskbot_get_diff_dates($start,$end) {
		$diff = strtotime($end) - strtotime($start); 
		return abs(round($diff / 86400)); 
	}
}

/**
 * Week range
 *
 * @throws error
 * @return 
 */
if (!function_exists('taskbot_get_weekrang')) {
	function taskbot_get_weekrang ($date_inweek,$days=6) {
		$return_array	= array();
		date_default_timezone_set (date_default_timezone_get());
		$week_start     = get_option( 'start_of_week');
		$week_slug		= taskbot_get_weekarray($week_start);
		$week_slug		= !empty($week_slug) ? $week_slug : '';
		$dt 			                = strtotime($date_inweek);
		$return_array['start_time']		= date('N', $dt) == $week_start ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last '.$week_slug, $dt));
        //$return_array['start_time']		= date ('Y-m-d', $dt);
		$end_week 		                = strtotime($return_array['start_time']."+".$days." day");
		$return_array['end_time']		=  date ('Y-m-d', $end_week);
		return $return_array;
	  }
}

/**
 * Week array
 *
 * @throws error
 * @return 
 */
if (!function_exists('taskbot_get_weekarray')) {
	function taskbot_get_weekarray ($key='0',$option='slug') {
		$week_days	= array(
			'0'	=> array('lable' => esc_html__('Sun','taskbot-hourly-addon'),'slug'	=> 'sunday'),
			'1'	=> array('lable' => esc_html__('Mon','taskbot-hourly-addon'),'slug'	=> 'monday'),
			'2'	=> array('lable' => esc_html__('Tue','taskbot-hourly-addon'),'slug'	=> 'tuesday'),
			'3'	=> array('lable' => esc_html__('Wed','taskbot-hourly-addon'),'slug'	=> 'wednesday'),
			'4'	=> array('lable' => esc_html__('Thurs','taskbot-hourly-addon'),'slug'=> 'thursday'),
			'5'	=> array('lable' => esc_html__('Fri','taskbot-hourly-addon'),'slug'	=> 'friday'),
			'6'	=> array('lable' => esc_html__('Sat','taskbot-hourly-addon'),'slug'	=> 'saturday')
		);

		if( isset($key) && $key != ''){
			$week_days	= !empty($week_days[$key][$option]) ? $week_days[$key][$option] : '';
		} else{
		
			$set_key	= get_option( 'start_of_week');
			if( $set_key != 0 ){
				$array1 	= array_slice($week_days, $set_key, 6);
				$array2 	= array_slice($week_days, 0, $set_key);
				$week_days	= array_merge($array1,$array2);
			}
		}
		return $week_days;
	  }
}

if( !function_exists('taskbot_date_range')){
    function taskbot_date_range($start=0, $end=0, $step = '+1 day', $output_format = 'Y-m-d' ) {

        $dates      = array();
        $current    = !empty($start) ? strtotime($start) : 0;
        $end        = !empty($end) ? strtotime($end) : 0;

        while( $current <= $end ) {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }
}
/**
 * Payment mode
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_payment_mode')){
	function taskbot_payment_mode($field='',$type='') {
		$lists  = array(
            'daily'     =>  array( 
                'title'     => esc_html__('Daily','taskbot-hourly-addon'),
                'name'      => esc_html__('Day','taskbot-hourly-addon'),
                'key'       => 'day',
            ),
            'weekly'    =>  array( 
                'title'     => esc_html__('Weekly','taskbot-hourly-addon'),
                'name'      => esc_html__('Week','taskbot-hourly-addon'),
                'key'       => 'week',
            ),
            'monthly'   =>  array( 
                'title' => esc_html__('Monthly','taskbot-hourly-addon'),
                'name'  => esc_html__('Month','taskbot-hourly-addon'),
                'key'       => 'month',
            ),
        );

		$lists  = apply_filters('taskbot_filter_payment_mode', $lists);
        if( !empty($field) ){
            $updated_array  = array();
            foreach($lists as $key => $val ){
                $updated_array[$key]    = !empty($val[$field]) ?$val[$field] : '';
            }
            $lists  = $updated_array;
        }
        if( !empty($type) ){
           
           $lists   = !empty($lists[$type]) ? $lists[$type] : '';
        }
        
        return $lists;
	}
}


/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_notification')){
	function taskbot_hourly_project_notification($data=array()) {
        $data['hours_submiation']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'seller_image',
                'tage'          => array('seller_name','buyer_name','project_title','project_link','project_id','proposal_id','buyer_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'buyer_proposal_timeslot_activity', 'text'=> esc_html__('View activity','taskbot-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to buyer after hours submitation','taskbot-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','taskbot-hourly-addon'),
                'content_title'         => esc_html__('Notification content','taskbot-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to seller after hours submitation','taskbot-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','taskbot-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{seller_name}}</strong> send you <strong>{{total_hours}}</strong> hours for the project <strong>{{project_title}}</strong>','taskbot-hourly-addon'),
                'tags'                  => __('
                                            {{buyer_name}}          — To display the buyer name.<br>
                                            {{seller_name}}         — To display the buyer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>
                                            {{total_hours}}         — To display the no of hours.<br>'),
            ),
        );
        $data['hours_decline']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'buyer_image',
                'tage'          => array('seller_name','buyer_name','project_title','project_link','project_id','proposal_id','seller_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'seller_proposal_timeslot_activity', 'text'=> esc_html__('View activity','taskbot-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to seller after hours decline','taskbot-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','taskbot-hourly-addon'),
                'content_title'         => esc_html__('Notification content','taskbot-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to seller after hours decline','taskbot-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','taskbot-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{buyer_name}}</strong> decline time for the project <strong>{{project_title}}</strong>','taskbot-hourly-addon'),
                'tags'                  => __('
                                            {{buyer_name}}          — To display the buyer name.<br>
                                            {{seller_name}}         — To display the buyer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>'),
            ),
        );
        $data['hours_approved']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'buyer_image',
                'tage'          => array('seller_name','buyer_name','project_title','project_link','project_id','proposal_id','seller_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'seller_proposal_timeslot_activity', 'text'=> esc_html__('View activity','taskbot-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to seller after hours approved','taskbot-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','taskbot-hourly-addon'),
                'content_title'         => esc_html__('Notification content','taskbot-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to seller after hours approved','taskbot-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','taskbot-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{buyer_name}}</strong> approved your time cart for the project <strong>{{project_title}}</strong>','taskbot-hourly-addon'),
                'tags'                  => __('
                                            {{buyer_name}}          — To display the buyer name.<br>
                                            {{seller_name}}         — To display the buyer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>
                                            {{total_hours}}         — To display the approved time.<br>'),
            ),
        );
        $data['unlock_hours']  = array(
            'type'      => 'proposals',
            'settings'  => array(
                'image_type'    => 'buyer_image',
                'tage'          => array('seller_name','buyer_name','project_title','project_link','project_id','proposal_id','seller_proposal_timeslot_activity','interval_name','total_hours'),
                'btn_settings'  => array('link_type'=>'seller_proposal_timeslot_activity', 'text'=> esc_html__('View activity','taskbot-hourly-addon'))
            ),
            'options'   => array(
                'title'                 => esc_html__('Notification to seller after unlock hours','taskbot-hourly-addon'),
                'tag_title'             => esc_html__('Notification setting variables','taskbot-hourly-addon'),
                'content_title'         => esc_html__('Notification content','taskbot-hourly-addon'),
                'enable_title'          => esc_html__('Enable/disable notification to seller after unlock hours','taskbot-hourly-addon'),
                'flash_message_title'   => esc_html__('Enable/disable flash message','taskbot-hourly-addon'),
                'flash_message_option'  => true,
                'content'               => __('<strong>{{buyer_name}}</strong> unlock your time cart for the project <strong>{{project_title}}</strong>','taskbot-hourly-addon'),
                'tags'                  => __('
                                            {{buyer_name}}          — To display the buyer name.<br>
                                            {{seller_name}}         — To display the buyer name.<br>
                                            {{project_title}}       — To display the project title.<br>
                                            {{project_link}}        — To display the project link.<br>
                                            {{project_id}}          — To display the project id.<br>
                                            {{proposal_id}}          — To display the proposal id.<br>
                                            {{interval_name}}       — To display the Interval title.<br>
                                            {{total_hours}}         — To display the approved time.<br>'),
            ),
        );
        return $data;
	}
    add_filter( 'taskbot_filter_list_notification', 'taskbot_hourly_project_notification');
}


/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_params')){
	function taskbot_hourly_project_params($param_value,$post_id,$param) {
        $post_data		= get_post_meta( $post_id, 'post_data', true );
		$post_data		= !empty($post_data) ? $post_data : array();
		switch ($param) {
            case "total_hours":
				$param_value	= !empty($post_data['total_hours']) ? esc_html($post_data['total_hours']) : '';
			break;
            case "interval_name":
				$param_value	= !empty($post_data['interval_name']) ? esc_html($post_data['interval_name']) : '';
			break;
            case "decline_detail":
				$param_value	= !empty($post_data['decline_detail']) ? esc_html($post_data['decline_detail']) : '';
			break;
        }
        return $param_value;
	}
    add_filter( 'taskbot_filter_notification_replaceparams', 'taskbot_hourly_project_params',10,3);
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_notification_button')){
	function taskbot_hourly_project_notification_button($button_html,$post_id,$settings,$show_option) {
        $btn_settings			= !empty($settings['btn_settings']) ? $settings['btn_settings'] : array();
		$link_class				= !empty($show_option) && $show_option === 'listing' ? 'tk-btn-solid' : '';
        if( !empty($btn_settings) ){
			$link_type	= !empty($btn_settings['link_type']) ? $btn_settings['link_type'] : '';
			$btn_link	= '';
			$post_data	= get_post_meta( $post_id, 'post_data', true);
			$post_data	= !empty($post_data) ? $post_data : array();
            
			if( !empty($link_type) && $link_type === 'buyer_proposal_timeslot_activity' ){
                $receiver_id	= !empty($post_data['buyer_id']) ? get_post_field( 'post_author', $post_data['buyer_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
                $transaction_id	= !empty($post_data['transaction_id']) ? $post_data['transaction_id'] : 0;
				$btn_link		= !empty($receiver_id) && !empty($proposal_id) ? Taskbot_Profile_Menu::taskbot_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id).'&transaction_id='.$transaction_id : "";
                $button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';
            } else if( !empty($link_type) && $link_type === 'seller_proposal_timeslot_activity' ){
                $receiver_id	= !empty($post_data['seller_id']) ? get_post_field( 'post_author', $post_data['seller_id'] ) : 0;
				$proposal_id	= !empty($post_data['proposal_id']) ? $post_data['proposal_id'] : 0;
                $transaction_id	= !empty($post_data['transaction_id']) ? $post_data['transaction_id'] : 0;
				$btn_link		= !empty($receiver_id) && !empty($proposal_id) ? Taskbot_Profile_Menu::taskbot_profile_menu_link('projects', $receiver_id, true, 'activity',$proposal_id).'&transaction_id='.$transaction_id : "";
                $button_html	= !empty($btn_settings['text']) ? '<a class="'.esc_attr($link_class).'" href="'.esc_url($btn_link).'">'.esc_html($btn_settings['text']).'</a>' : '';
            }
        }
        return $button_html;
	}
    add_filter( 'taskbot_filter_notification_button', 'taskbot_hourly_project_notification_button',10,4);
}

/**
 * Add email for buyer
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_buyer_email')){
	function taskbot_hourly_project_buyer_email($buyer_email) {
        $new_array  = array(
            /* Email to buyer on hourly request from seller */
            array(
                'id'      => 'divider_hourly_request_send_buyer_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Hourly project request', 'taskbot-hourly-addon' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'hourly_request_send_buyer_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'taskbot-hourly-addon'),
                'subtitle' => esc_html__('Email to buyer on hourly requst.', 'taskbot-hourly-addon'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'hourly_request_send_buyer_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'taskbot-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'taskbot-hourly-addon' ),
                'default' 	=> esc_html__( 'Hourly request on project', 'taskbot-hourly-addon'),
                'required'  => array('hourly_request_send_buyer_email_switch','equals','1')
            ),
            array(
                'id'      => 'hourly_request_send_buyer_email_information',
                'desc'    => wp_kses( __( '{{buyer_name}} — To display the buyer name.<br>
                            {{seller_name}} — To display the seller name.<br>
                            {{project_title}} — To display the project title.<br>
                            {{project_link}} — To display the project link.<br>'
                            , 'taskbot-hourly-addon' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'taskbot-hourly-addon' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('hourly_request_send_buyer_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'hourly_request_send_buyer_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'taskbot-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add text.', 'taskbot-hourly-addon' ),
                'default' 	=> esc_html__( 'Hello {{buyer_name}},', 'taskbot-hourly-addon'),
                'required'  => array('hourly_request_send_buyer_email_switch','equals','1')
            ),
            array(
                'id'        => 'hourly_request_send_buyer_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( '{{seller_name}} send you a hourly project request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'taskbot-hourly-addon'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'taskbot-hourly-addon' ),
                'required'  => array('hourly_request_send_buyer_email_switch','equals','1')
            ),
        );
        
        return array_merge($buyer_email,$new_array);
	}
    add_filter( 'taskbot_filter_buyer_email_fields', 'taskbot_hourly_project_buyer_email');
}

/**
 * Add email for seller
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_project_seller_email')){
	function taskbot_hourly_project_seller_email($seller_email) {
        $new_seller_array  = array(
            /* Hourly request approved from buyer */
            array(
                'id'      => 'divider_hourly_request_approve_seller_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Hourly request approved', 'taskbot-hourly-addon' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'hourly_request_approve_seller_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'taskbot-hourly-addon'),
                'subtitle' => esc_html__('Email to seller on hourly request approved.', 'taskbot-hourly-addon'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'hourly_request_approve_seller_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'taskbot-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'taskbot-hourly-addon' ),
                'default' 	=> esc_html__( 'Project hourly request approved', 'taskbot-hourly-addon'),
                'required'  => array('hourly_request_approve_seller_email_switch','equals','1')
            ),
            array(
                'id'      => 'divider_hourly_request_approve_seller_email_information',
                'desc'    => wp_kses( __( '{{buyer_name}} — To display the buyer name.<br>
                            {{seller_name}} — To display the seller name.<br>
                            {{project_title}} — To display the project title.<br>
                            {{project_link}} — To display the project link.<br>'
                            , 'taskbot-hourly-addon' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'taskbot-hourly-addon' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('hourly_request_approve_seller_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'hourly_request_approve_seller_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'taskbot-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add text.', 'taskbot-hourly-addon' ),
                'default' 	=> esc_html__( 'Hello {{seller_name}},', 'taskbot-hourly-addon'),
                'required'  => array('hourly_request_approve_seller_email_switch','equals','1')
            ),
            array(
                'id'        => 'hourly_request_approve_seller_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( 'Congratulation! {{buyer_name}} have approve your project hourly request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'taskbot-hourly-addon'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'taskbot-hourly-addon' ),
                'required'  => array('hourly_request_approve_seller_email_switch','equals','1')
            ),

            /* Hourly request decline from buyer */
            array(
                'id'      => 'divider_hourly_request_decline_seller_email_templates',
                'type'    => 'info',
                'title'   => esc_html__( 'Hourly request declined', 'taskbot-hourly-addon' ),
                'style'   => 'info',
            ),
            array(
                'id'       => 'hourly_request_decline_seller_email_switch',
                'type'     => 'switch',
                'title'    => esc_html__('Send email', 'taskbot-hourly-addon'),
                'subtitle' => esc_html__('Email to seller on hourly request declined.', 'taskbot-hourly-addon'),
                'default'  => true,
            ),
            array(
                'id'      	=> 'hourly_request_decline_seller_email_subject',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Subject', 'taskbot-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add email subject.', 'taskbot-hourly-addon' ),
                'default' 	=> esc_html__( 'Project hourly request declined', 'taskbot-hourly-addon'),
                'required'  => array('hourly_request_decline_seller_email_switch','equals','1')
            ),
            array(
                'id'      => 'divider_hourly_request_decline_seller_email_information',
                'desc'    => wp_kses( __( '{{buyer_name}} — To display the buyer name.<br>
                            {{seller_name}} — To display the seller name.<br>
                            {{project_title}} — To display the project title.<br>
                            {{project_link}} — To display the project link.<br>
                            {{decline_detail}} — To display the decline detail.<br>'
                            , 'taskbot-hourly-addon' ),
                array(
                    'a'	=> array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                ) ),
                'title'     => esc_html__( 'Email setting variables', 'taskbot-hourly-addon' ),
                'type'      => 'info',
                'class'     => 'dc-center-content',
                'icon'      => 'el el-info-circle',
                'required'  => array('hourly_request_decline_seller_email_switch','equals','1')
            ),
            array(
                'id'      	=> 'hourly_request_decline_seller_email_greeting',
                'type'    	=> 'text',
                'title'   	=> esc_html__( 'Greeting', 'taskbot-hourly-addon' ),
                'desc'    	=> esc_html__( 'Please add text.', 'taskbot-hourly-addon' ),
                'default' 	=> esc_html__( 'Hello {{seller_name}},', 'taskbot-hourly-addon'),
                'required'  => array('hourly_request_decline_seller_email_switch','equals','1')
            ),
            array(
                'id'        => 'hourly_request_decline_seller_email_content',
                'type'      => 'textarea',
                'default'   => wp_kses( __( 'Oho! A project hourly request has been declined by {{buyer_name}} with the reason of <br/> {{decline_detail}} <br />Please click on the button below to view the decline details.<br />{{project_link}}', 'taskbot-hourly-addon'),
                array(
                    'a'	=> array(
                    'href'  => array(),
                    'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )),
                'title'     => esc_html__( 'Email contents', 'taskbot-hourly-addon' ),
                'required'  => array('hourly_request_decline_seller_email_switch','equals','1')
            ),

        );
        return array_merge($seller_email,$new_seller_array);
    }
    add_filter( 'taskbot_filter_seller_email_fields', 'taskbot_hourly_project_seller_email');
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_filter_invoice_title')){
	function taskbot_filter_invoice_title($invoice_id) {
        $invoice_title  = '';
		$order_data     = get_post_meta( $invoice_id, 'cus_woo_product_data',true );
        if( !empty($order_data['project_type']) && $order_data['project_type'] === 'hourly' ){
            $project_id     = !empty($order_data['project_id']) ? $order_data['project_id'] : '';
            $project_title  = !empty($project_id) ? get_the_title( $project_id ) : '';
            $invoice_title  = !empty($order_data['interval_name']) && !empty($project_title) ? $project_title . ' ('. $order_data['interval_name'].')' : '';
        }

        return $invoice_title;
	}
    add_filter( 'taskbot_filter_invoice_title', 'taskbot_filter_invoice_title');
}

/**
 * Add Invoice URL
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_filter_invoice_url')){
	function taskbot_filter_invoice_url($invoice_url='',$invoice_id=0) {
        global $current_user;
		$order_data     = get_post_meta( $invoice_id, 'cus_woo_product_data',true );
        if( !empty($order_data['project_type']) && $order_data['project_type'] === 'hourly' ){
            $invoice_url    = Taskbot_Profile_Menu::taskbot_profile_menu_link('invoices', $current_user->ID, true, 'hourly-detail', intval($invoice_id));
        }

        return $invoice_url;
	}
    add_filter( 'taskbot_filter_invoice_url', 'taskbot_filter_invoice_url',10,2);
}

/**
 * Requried field for job creation
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_add_tooltip_list')){
	function taskbot_add_tooltip_list($list=array()) {
        $list['add_max_hours']  = esc_html__('This would be a maximum working hours limit for a freelancer.','taskbot-hourly-addon');
        return $list;
	}
    add_filter( 'taskbot_filter_tooltip_array', 'taskbot_add_tooltip_list');
}

/**
 * Add notifications
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_duplicate_job_filter')){
	function taskbot_hourly_duplicate_job_filter($meta_keys=array(),$post_id=0) {
        $project_type   = get_post_meta( $post_id, 'project_type',true );
        $project_type   = !empty($project_type) ? $project_type : '';

        if( !empty($project_type) && $project_type === 'hourly' ){
            $meta_keys[]    = 'min_price';
            $meta_keys[]    = 'max_price';
            $meta_keys[]    = 'max_hours';
            $meta_keys[]    = 'payment_mode';
        }

        return $meta_keys;
	}
    add_filter( 'taskbot_duplicate_job_filter', 'taskbot_hourly_duplicate_job_filter',10,2);
}