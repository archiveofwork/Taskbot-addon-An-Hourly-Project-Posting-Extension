<?php
/**
 * hourly monthly intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('taskbot_hourly_monthly_intervals')) {
    function taskbot_hourly_monthly_intervals($proposal_id,$current_week,$intervals ) {
        date_default_timezone_set (date_default_timezone_get());
        global $current_user;
        $hiring_string          = !empty($proposal_id) ? get_post_meta( $proposal_id, 'hiring_date',true ) : 0;
        $proposal_date          = !empty($hiring_string) ? date('Y-m-d',strtotime($hiring_string)) : 0;
        $start_week_date        = date('Y-m-d');
        if( !empty($intervals) ){
            ob_start();
            ?>
            <div class="tk-calendar">
                <select class="form-control tk-selectprice" id="tb_order_type">                 
                    <?php
                        foreach($intervals as $key => $val ){
                            $working_date   = !empty($key) ? date('M Y',intval($key)) : '';
                            $activ_class    = '';
                            if( !empty($current_week) && $current_week === $key ){
                                $activ_class    = "selected";
                            }
                            ?>
                            <option <?php echo esc_attr($activ_class);?> data-url="<?php Taskbot_Profile_Menu::taskbot_profile_menu_link('projects', $current_user->ID, '', 'activity',$proposal_id)?>&transaction_id=<?php echo intval($key);?>"><?php echo do_shortcode($working_date);?></option>
                    <?php } ?>
                </select>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('taskbot_hourly_monthly_intervals', 'taskbot_hourly_monthly_intervals', 10, 3);
}

/**
 * Daily time slot
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_day_time_slots')){
	function taskbot_hourly_day_time_slots($proposal_id=0,$transaction_id=0,$time_slots=array()) {
        $date_format       = get_option( 'date_format' );
        $proposal_meta      = get_post_meta( $proposal_id, 'proposal_meta',true );
        $proposal_meta      = !empty($proposal_meta) ? $proposal_meta : array(); 
        $start_time_string  = !empty($transaction_id) ? intval($transaction_id) : 0;
        $time_slot_status   = !empty($time_slots[$start_time_string]['status']) ? $time_slots[$start_time_string]['status'] : '';
        $post_status        = get_post_status( $proposal_id );
        $updated_classs     = "";
        if( empty($time_slot_status) || $time_slot_status === 'draft' || $time_slot_status === 'decline' ){
            if(!empty($post_status) && $post_status === 'hired'){
                $updated_classs = 'tb_add_timeslot';
            }
        }

        ob_start();
        if( !empty($start_time_string) ){ 
            $time_value         = !empty($time_slots[$start_time_string]['slots'][$start_time_string]['slot_time']) ? $time_slots[$start_time_string]['slots'][$start_time_string]['slot_time'] : '';
            $details            = !empty($time_slots[$start_time_string]['slots'][$start_time_string]['details']) ? $time_slots[$start_time_string]['slots'][$start_time_string]['details'] : '';
            $time_string        = !empty($time_slots[$start_time_string]['slots'][$start_time_string]['time_string']) ? $time_slots[$start_time_string]['slots'][$start_time_string]['time_string'] : '';
            $list_date_formated = date_i18n($date_format,$start_time_string);?>
            <li>
                <span><?php echo esc_html($list_date_formated);?></span>
                <div class="tk-today-timepopup">
                    <input type="text" readonly
                        data-timeslot_date="<?php echo intval($start_time_string);?>"
                        data-start_time_string="<?php echo intval($start_time_string);?>" 
                        data-time_string="<?php echo esc_attr($time_string);?>" 
                        value="<?php echo esc_attr($time_string);?>" 
                        class="form-control <?php echo esc_attr($updated_classs);?>" 
                        data-time="<?php echo intval($start_time_string);?>" 
                        data-proposal_id="<?php echo intval($proposal_id);?>" 
                        data-formated_date="<?php echo esc_attr($list_date_formated);?>" 
                        placeholder="<?php esc_attr_e('Add time','taskbot-hourly-addon');?>">
                    <div class="d-none" id="tb_<?php echo esc_attr($start_time_string);?>"><?php echo esc_html($details);?></div>
                </div>
            </li>
        <?php 
        }
        echo ob_get_clean();
	}
    add_action('taskbot_hourly_day_time_slots', 'taskbot_hourly_day_time_slots',10,3);
}

/**
 * Monthly time slot
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_month_time_slots')){
	function taskbot_hourly_month_time_slots($proposal_id=0,$transaction_id=0,$time_slots=array()) {
        $date_format       = get_option( 'date_format' );
        $start_time         = !empty($transaction_id) ? date('Y-m-01',$transaction_id) : '';
        $end_time           = !empty($transaction_id) ? date('Y-m-t',$transaction_id) : '';
        $list_date_rang     = !empty($start_time) && !empty($end_time) ? taskbot_date_range($start_time,$end_time,'+1 day','Y-m-d') : array();
        $start_time_string  = !empty($start_time) ? strtotime($start_time) : 0;
        $hiring_string      = !empty($proposal_id) ? get_post_meta( $proposal_id, 'hiring_date',true ) : 0;
        $hiring_date        = !empty($hiring_string) ? strtotime($hiring_string) : 0;
        $post_status        = get_post_status( $proposal_id );
        $hiring_month           = date('m',$hiring_date);
        $hiring_year            = date('Y',$hiring_date);
        $hiring_day             = date('d',$hiring_date);
        $formated_hiring_date   = sprintf("%04d%02d%02d", $hiring_year, $hiring_month, $hiring_day);
        $formated_hiring_date   = !empty($formated_hiring_date) ? intval($formated_hiring_date) : 0;
        $current_string         = time();
        $formated_current_date  = sprintf("%04d%02d%02d", date('Y'), date('m'), date('d'));
        $formated_current_date  = !empty($formated_current_date) ? intval($formated_current_date) : 0;
        $time_slot_status       = !empty($time_slots[$start_time_string]['status']) ? $time_slots[$start_time_string]['status'] : '';
        $li_classs     = "";
        if( empty($time_slot_status) || in_array($time_slot_status,array('draft','decline')) ){
            $li_classs = 'tb_add_timeslot';
        }

        ob_start();
        if( !empty($list_date_rang) ){ 
            foreach($list_date_rang as $list_date){
                $timeslot_date      = strtotime($list_date);
                $updated_classs     = $li_classs;
                $timeslot_date_month           = date('m',$timeslot_date);
                $timeslot_date_year            = date('Y',$timeslot_date);
                $timeslot_date_day             = date('d',$timeslot_date);
                $formated_timeslot_date_date   = sprintf("%04d%02d%02d", $timeslot_date_year, $timeslot_date_month, $timeslot_date_day);
                $formated_timeslot_date_date   = !empty($formated_timeslot_date_date) ? intval($formated_timeslot_date_date) : 0; 
                $formated_hiring_date;

                if( !empty($hiring_string) 
                    && !empty($timeslot_date)
                    && !empty($post_status) && $post_status === 'hired'
                    && $formated_timeslot_date_date >= $formated_hiring_date
                    && $formated_timeslot_date_date <= $formated_current_date){
                    $updated_classs     = $li_classs;
                } else {
                    $updated_classs = 'tb_disbale_date';
                }
                
                $time_value         = !empty($time_slots[$start_time_string]['slots'][$timeslot_date]['slot_time']) ? $time_slots[$start_time_string]['slots'][$timeslot_date]['slot_time'] : '';
                $details            = !empty($time_slots[$start_time_string]['slots'][$timeslot_date]['details']) ? $time_slots[$start_time_string]['slots'][$timeslot_date]['details'] : '';
                $time_string         = !empty($time_slots[$start_time_string]['slots'][$timeslot_date]['time_string']) ? $time_slots[$start_time_string]['slots'][$timeslot_date]['time_string'] : '';
                $list_date_formated = date_i18n($date_format,$timeslot_date);
                $liclass    = 'tk-timslot-li';
                if( isset($time_value) && $time_value > 0 ){
                    $liclass    = 'tk-added-time';
                }
                ?>
                <li class="<?php echo esc_attr($liclass);?>">
                    <span><?php echo date_i18n('l',$timeslot_date);?></span>
                    <em><?php echo esc_html($list_date_formated);?></em>
                    <a href="javascript:void(0)" data-timeslot_date="<?php echo intval($timeslot_date);?>" data-time_string="<?php echo esc_attr($time_string);?>" class="<?php echo esc_attr($updated_classs);?>" data-time="<?php echo intval($start_time_string);?>" data-proposal_id="<?php echo intval($proposal_id);?>" data-formated_date="<?php echo esc_attr($list_date_formated);?>">
                        <?php 
                            if( isset($time_string) ){
                                echo esc_html($time_string);
                                if( !empty($updated_classs) && $updated_classs != 'tb_disbale_date' ){ ?>
                                    <i class="icon-edit-3"></i>
                                <?php
                                }
                            } else {
                                if( !empty($updated_classs) && $updated_classs != 'tb_disbale_date' ){
                                    esc_html_e('Add time','taskbot-hourly-addon');
                                    ?>
                                    <i class="icon-plus"></i>
                                <?php
                                }
                            }
                        ?>
                    </a>
                </li>
        <?php }
        }
        echo ob_get_clean();
	}
    add_action('taskbot_hourly_month_time_slots', 'taskbot_hourly_month_time_slots',10,3);
}

/**
 * Weekly time slot
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('taskbot_hourly_time_slots')){
	function taskbot_hourly_time_slots($proposal_id=0,$date_rang=array(),$transaction_id=0,$time_slots=array()) {
        global $taskbot_settings;
        $allow_hour_times     =  !empty($taskbot_settings['allow_hour_times']) ? $taskbot_settings['allow_hour_times'] : 'past';
        $date_format        = get_option( 'date_format' );
        $start_time         = !empty($date_rang['start_time']) ? $date_rang['start_time'] : '';
        $end_time           = !empty($date_rang['end_time']) ? $date_rang['end_time'] : '';
        $hiring_string      = !empty($proposal_id) ? get_post_meta( $proposal_id, 'hiring_date',true ) : 0;
        $hiring_string      = !empty($hiring_string) ? strtotime($hiring_string) : 0;
        $post_status        = get_post_status( $proposal_id );
        $hiring_month           = date('m',$hiring_string);
        $hiring_year            = date('Y',$hiring_string);
        $hiring_day             = date('d',$hiring_string);
        $formated_hiring_date   = sprintf("%04d%02d%02d", $hiring_year, $hiring_month, $hiring_day);
        $formated_hiring_date   = !empty($formated_hiring_date) ? intval($formated_hiring_date) : 0;
        $current_string         = time();
        $formated_current_date  = sprintf("%04d%02d%02d", date('Y'), date('m'), date('d'));
        $formated_current_date  = !empty($formated_current_date) ? intval($formated_current_date) : 0;

        $list_date_rang     = !empty($start_time) && !empty($end_time) ? taskbot_date_range($start_time,$end_time) : array();
        $start_time_string  = !empty($start_time) ? strtotime($start_time) : 0;
        $time_slot_status   = !empty($time_slots[$transaction_id]['status']) ? $time_slots[$transaction_id]['status'] : '';
        $current_string     = time();
        $li_classs          = "";

        if( in_array($time_slot_status,array('draft','decline')) ){
            $li_classs = 'tb_add_timeslot';
        }

        ob_start();
        if( !empty($list_date_rang) ){ 
            foreach($list_date_rang as $list_date){
                $timeslot_date      = strtotime($list_date);
                $li_class   ='tk-li-enable';
                $readonly   = 'readonly';

                if( !empty($hiring_string) 
                    && !empty($list_date)
                    && !empty($post_status) && $post_status === 'hired'
                    && strtotime($list_date) >= strtotime($formated_hiring_date)
                    && strtotime($list_date) <= strtotime($formated_current_date)
                    && $allow_hour_times == 'no' 
                    
                ){
                    $updated_classs     = $li_classs;
                    $readonly           = '';
                }else if( !empty($hiring_string) 
                && !empty($list_date)
                && !empty($post_status) && $post_status === 'hired'
                && strtotime($list_date) <= strtotime($formated_hiring_date)
                && strtotime($list_date) <= strtotime($formated_current_date)
                && $allow_hour_times == 'past' 
                
                ){
                    $updated_classs     = $li_classs;
                    $readonly           = '';
                } else if( !empty($hiring_string) 
                && !empty($list_date)
                && !empty($post_status) && $post_status === 'hired'
                && strtotime($list_date) >= strtotime($start_time) 
                && strtotime($list_date) <= strtotime($end_time)
                && $allow_hour_times == 'both' 
                
                ){
                    $updated_classs     = $li_classs;
                    $readonly           = '';
                }else {
                    $updated_classs = 'tb_disbale_date';
                    $li_class       ='tk-week-disable';
                }

                

                $time_value         = !empty($time_slots[$start_time_string]['slots'][$timeslot_date]['slot_time']) ? $time_slots[$start_time_string]['slots'][$timeslot_date]['slot_time'] : '';
                $details            = !empty($time_slots[$start_time_string]['slots'][$timeslot_date]['details']) ? $time_slots[$start_time_string]['slots'][$timeslot_date]['details'] : '';
                $time_string         = !empty($time_slots[$start_time_string]['slots'][$timeslot_date]['time_string']) ? $time_slots[$start_time_string]['slots'][$timeslot_date]['time_string'] : '';

                
                $list_date_formated = date_i18n($date_format,$timeslot_date);?>
                <li class="<?php echo esc_attr($li_class);?>">
                    <span><?php echo esc_html($list_date_formated);?></span>
                    <input <?php echo esc_attr($readonly);?> type="text" data-timeslot_date="<?php echo intval($timeslot_date);?>" data-time_string="<?php echo esc_attr($time_string);?>" value="<?php echo esc_attr($time_value);?>" class="form-control <?php echo esc_attr($updated_classs);?>" data-time="<?php echo intval($start_time_string);?>" data-proposal_id="<?php echo intval($proposal_id);?>" data-formated_date="<?php echo esc_attr($list_date_formated);?>" placeholder="<?php esc_attr_e('Add time','taskbot-hourly-addon');?>">

                    <div class="d-none" id="tb_<?php echo esc_attr($timeslot_date);?>"><?php echo esc_html($details);?></div>
                </li>
        <?php }
        }
        echo ob_get_clean();
	}
    add_action('taskbot_hourly_time_slots', 'taskbot_hourly_time_slots',10,4);
}

/**
 * hourly daily intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('taskbot_hourly_daily_intervals')) {
    function taskbot_hourly_daily_intervals($proposal_id,$current_week ) {
        date_default_timezone_set (date_default_timezone_get());
        global $current_user;
        $hiring_string          = !empty($proposal_id) ? get_post_meta( $proposal_id, 'hiring_date',true ) : 0;
        $proposal_date          = !empty($hiring_string) ? date('Y-m-d',strtotime($hiring_string)) : 0;
        $start_week_date        = date('Y-m-d');
        $date_format            = get_option( 'date_format' );
        $intervals              = taskbot_date_range($proposal_date,$start_week_date);
        if( !empty($proposal_date) ){
            ob_start();
            ?>
            <div class="tk-calendar">
                <select class="form-control tk-selectprice" id="tb_order_type">                 
                    <?php
                        foreach($intervals as $key => $val ){
                            $stringtime     = !empty($val) ? strtotime($val) : 0;
                            $working_date   = !empty($stringtime) ? date_i18n($date_format,$stringtime) : '';
                            $activ_class    = '';
                            if( !empty($current_week) && $current_week === $stringtime ){
                                $activ_class    = "selected";
                            }
                            ?>
                            <option <?php echo esc_attr($activ_class);?> data-url="<?php Taskbot_Profile_Menu::taskbot_profile_menu_link('projects', $current_user->ID, '', 'activity',$proposal_id)?>&transaction_id=<?php echo intval($stringtime);?>"><?php echo do_shortcode($working_date);?></option>
                    <?php } ?>
                </select>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
    add_action('taskbot_hourly_daily_intervals', 'taskbot_hourly_daily_intervals', 10, 3);
}

/**
 * hourly daily intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('taskbot_hourly_list')) {
    function taskbot_hourly_list($proposal_id=0,$transaction_id=0,$time_slots=array() ) {
        $date_format            = get_option( 'date_format' );
        $count_time_slots       = !empty($time_slots[$transaction_id]['slots']) && is_array($time_slots[$transaction_id]['slots']) ? count($time_slots[$transaction_id]['slots']) : 0;
        
        ob_start();
        if( !empty($time_slots[$transaction_id]['slots']) ){ ?>
            <table class="table tk-proinvoices_table tk-timecard-table" >
                <thead>
                    <tr>
                        <th><?php esc_html_e('Date','taskbot-hourly-addon');?></th>
                        <th><?php esc_html_e('Description','taskbot-hourly-addon');?></th>
                        <th><?php esc_html_e('Hours','taskbot-hourly-addon');?></th>
                    </tr>
                </thead>
                <tbody>              
                    <?php
                        foreach($time_slots[$transaction_id]['slots'] as $key => $val ){
                            $working_date   = !empty($key) ? date_i18n($date_format,$key) : '';
                            $details        = !empty($val['details']) ? $val['details'] : "";
                            $slot_time      = isset($val['slot_time']) ? $val['slot_time'] : 0;
                            ?>
                            <tr>
                                <td data-label="<?php esc_attr_e('Date','taskbot-hourly-addon');?>"><?php echo esc_html($working_date);?></td>
                                <td data-label="<?php esc_attr_e('Description','taskbot-hourly-addon');?>"><p id="tb_<?php echo intval($key);?>"><?php echo esc_html($details);?></p></td>
                                <td data-label="<?php esc_attr_e('Hour','taskbot-hourly-addon');?>"><?php echo esc_attr($slot_time);?></td>
                            </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php 
            if( !empty($count_time_slots) && $count_time_slots > 3 ){?>
                <div class="show-more">
                    <a href="javascript:void(0);" class="tk-readmorebtn tk-show_more" data-show_more="<?php esc_attr_e('Load more','taskbot-hourly-addon');?>" data-show_less="<?php esc_attr_e('Load more','taskbot-hourly-addon');?>"><?php esc_html_e('Load more','taskbot-hourly-addon');?></a>
                </div>
            <?php } ?>
        <?php } else {
            do_action( 'taskbot_empty_listing', esc_html__('No hourly project activities found', 'taskbot-hourly-addon'));
        }
        echo ob_get_clean();
    }
    add_action('taskbot_hourly_list', 'taskbot_hourly_list',10,3);
}

/**
 * hourly status
 *
 * @throws error
 * @return
 */
if (!function_exists('taskbot_hourly_status')) {
    function taskbot_hourly_status($status ) { 
        ob_start();
        $label          = '';
        $status_class   = '';
        switch($status){
            case 'pending':
                $label          = esc_html__('Pending', 'taskbot-hourly-addon');
                $status_class   = 'tk-project-tag tk-awaiting';
                break;
            case 'draft':
                $label          = esc_html__('Drafted', 'taskbot-hourly-addon');
                $status_class   = 'tk-project-tag';
                break;
                break;
            case 'completed':
                $label          = esc_html__('Completed', 'taskbot-hourly-addon');
                $status_class   = 'tk-project-tag tk-success-tag';
                break;
            case 'decline':
                $label          = esc_html__('Decline', 'taskbot-hourly-addon');
                $status_class   = 'tk-project-tag tk-canceled';
                break;
            default:
                $label          = esc_html__('New', 'taskbot-hourly-addon');
                $status_class   = 'tk-project-tag';
                break;
        }
        if( !empty($label) ){
            ob_start();
            ?>
                <span class="<?php echo esc_attr($status_class);?>"><?php echo esc_html($label);?></span>
            <?php
            echo ob_get_clean();
        }
        echo ob_get_clean();
    }
    add_action('taskbot_hourly_status', 'taskbot_hourly_status');
}

/**
 * hourly daily intervals
 *
 * @throws error
 * @return
 */
if (!function_exists('taskbot_hourly_stats_status')) {
    function taskbot_hourly_stats_status($time_slots=array(),$transaction_id=0,$price=0,$payment_mode='',$proposal_id ) { 
        ob_start();
        global $current_user;
        $user_type          = taskbot_get_user_type($current_user->ID );
        $total_amount       = 0;
        $current_pending    = 0;
        $total_pending      = 0;
        $total_completed    = 0;
        $remaning_amount    = get_post_meta( $proposal_id, 'remaining_amount',true );
        $remaning_amount    = isset($remaning_amount) ? $remaning_amount : 0;
        
        $mode_title = esc_html__('Day','taskbot-hourly-addon');
        if( !empty($payment_mode) && $payment_mode === 'month'){
            $mode_title = esc_html__('Month','taskbot-hourly-addon');
        } else if( !empty($payment_mode) && $payment_mode === 'week' ){
            $mode_title = esc_html__('Week','taskbot-hourly-addon');
        } else if( !empty($payment_mode) && $payment_mode === 'day' ){
            $mode_title = esc_html__('Day','taskbot-hourly-addon');
        }

        if( !empty($time_slots) ){
            foreach($time_slots as $key => $val ){
                $total_time     = !empty($val['total_time']) ? $val['total_time'] : 0;
                $status         = !empty($val['status']) ? $val['status'] : '';
                $total_amount   = $total_amount + $total_time;
                if( !empty($status) && $status === 'completed' ){
                    $total_completed   = ($total_completed + $total_time)*$price;
                } else if( !empty($status) && $status === 'pending' ){
                    $total_pending   = ($total_pending + $total_time)*$price;
                }
                if( !empty($status) && $status === 'pending' && !empty($key) && $key === $transaction_id ){
                    $current_pending   = ($current_pending + $total_time)*$price;
                }
                
            }
        }
        
        if( !empty($time_slots) ){
            ob_start();
            ?>
            <div class="tk-counterinfo ">
                <ul class="tk-counterinfo_list">
                    <li>
                        <strong class="tk-counterinfo_escrow"><i class="icon-clock"></i></strong>
                        <span>
                            <?php 
                                if( !empty($payment_mode) && $payment_mode === 'month'){
                                    esc_html_e('Total pending for this month','taskbot-hourly-addon');
                                } else if( !empty($payment_mode) && $payment_mode === 'week' ){
                                    esc_html_e('Total pending for this week','taskbot-hourly-addon');
                                } else if( !empty($payment_mode) && $payment_mode === 'day' ){
                                    esc_html_e('Total pending for this day','taskbot-hourly-addon');
                                }
                            ?>
                        </span>
                        <h5><?php taskbot_price_format($current_pending);?></h5>
                    </li>
                    <?php if( !empty($user_type) && $user_type === 'buyers' ){
                        $carried_text   = sprintf(__("Your remaining total exessive amount will be carried forward to the next %s for your use.","taskbot-hourly-addon"),$payment_mode);?>
                        <li>
                            <?php do_action('taskbot_tooltip', '<i class="icon-alert-circle"></i>', '',$carried_text);?>
                            <strong class="tk-counterinfo_carried"><i class="icon-git-branch"></i></strong>
                            <span><?php esc_html_e('Total carried forward','taskbot-hourly-addon');?></span>
                            <h5><?php taskbot_price_format($remaning_amount);?></h5>
                        </li>
                    <?php } ?>
                    <li>
                        <strong class="tk-counterinfo_earned"><i class="icon-briefcase"></i></strong>
                        <span><?php esc_html_e('Total pending amount','taskbot-hourly-addon');?></span>
                        <h5><?php taskbot_price_format($total_pending);?></h5>
                    </li>
                    <li>
                        <strong class="tk-counterinfo_remaining"><i class="icon-dollar-sign"></i></strong>
                        <span><?php esc_html_e('Total paid till date','taskbot-hourly-addon');?></span>
                        <h5><?php taskbot_price_format($total_completed);?></h5>
                    </li>
                </ul>
            </div>
            <?php
            echo ob_get_clean();
        }
        echo ob_get_clean();
    }
    add_action('taskbot_hourly_stats_status', 'taskbot_hourly_stats_status',10,5);
}