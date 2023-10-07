<?php

/**
 *
 * Class 'HourlyAddonEmails' defines User active or deactive
 *
 * @package    Taskbot_Hourly_Addon
 * @subpackage Taskbot_Hourly_Addon/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
/* get the EmailHelper class */
if (!class_exists('Taskbot_Email_helper') && in_array('taskbot/init.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    require_once WP_PLUGIN_DIR . '/taskbot/helpers/EmailHelper.php';
}

if (!class_exists('HourlyAddonEmails') && class_exists('Taskbot_Email_helper')) {
    class HourlyAddonEmails extends Taskbot_Email_helper
    {
        /* hourly project request buyer email */
        public function hourly_project_request_buyer_email($params = '')
        {
            global  $taskbot_settings;
            extract($params);
            $email_to           = !empty($buyer_email) ? $buyer_email : '';
            $buyer_name         = !empty($buyer_name) ? $buyer_name : '';
            $seller_name         = !empty($seller_name) ? $seller_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';

            $subject_default         = esc_html__('Hourly request on project', 'taskbot-hourly-addon'); //default email subject
            $contact_default         = wp_kses(
                __('{{seller_name}} send you a hourly project request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'taskbot-hourly-addon'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject            = !empty($taskbot_settings['hourly_request_send_buyer_email_subject']) ? $taskbot_settings['hourly_request_send_buyer_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty($taskbot_settings['hourly_request_send_buyer_email_content']) ? $taskbot_settings['hourly_request_send_buyer_email_content'] : $contact_default; //getting content

            $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{buyer_name}}", $buyer_name, $email_content);
            $email_content = str_replace("{{seller_name}}", $seller_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'buyer_name';
            $greeting['greet_value']      = $buyer_name;
            $greeting['greet_option_key'] = 'hourly_request_send_buyer_email_greeting';

            $body   = $this->taskbot_email_body($email_content, $greeting);
            $body   = apply_filters('taskbot_buyer_project_hourly_request_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* hourly project request approve seller email */
        public function hourly_project_request_approve_seller_email($params = '')
        {
            global  $taskbot_settings;
            extract($params);

            $email_to           = !empty($seller_email) ? $seller_email : '';
            $buyer_name         = !empty($buyer_name) ? $buyer_name : '';
            $seller_name         = !empty($seller_name) ? $seller_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';

            $subject_default         = esc_html__('Project hourly request approved', 'taskbot-hourly-addon'); //default email subject
            $contact_default         = wp_kses(
                __('Congratulation! {{buyer_name}} have approve your project hourly request.<br/>Please click on the button below to view the project <br/> {{project_link}}', 'taskbot-hourly-addon'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject            = !empty($taskbot_settings['hourly_request_approve_seller_email_subject']) ? $taskbot_settings['hourly_request_approve_seller_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty($taskbot_settings['hourly_request_approve_seller_email_content']) ? $taskbot_settings['hourly_request_approve_seller_email_content'] : $contact_default; //getting content

            $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{seller_name}}", $seller_name, $email_content);
            $email_content = str_replace("{{buyer_name}}", $buyer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'seller_name';
            $greeting['greet_value']      = $seller_name;
            $greeting['greet_option_key'] = 'hourly_request_approve_seller_email_greeting';

            $body   = $this->taskbot_email_body($email_content, $greeting);
            $body   = apply_filters('taskbot_seller_project_hourly_request_approve_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* hourly project request decline seller email */
        public function hourly_project_request_decline_seller_email($params = '')
        {
            global  $taskbot_settings;
            extract($params);

            $email_to           = !empty($seller_email) ? $seller_email : '';
            $buyer_name         = !empty($buyer_name) ? $buyer_name : '';
            $seller_name        = !empty($seller_name) ? $seller_name : '';
            $project_title      = !empty($project_title) ? $project_title : '';
            $project_link       = !empty($project_link) ? $project_link : '';
            $decline_detail     = !empty($decline_detail) ? $decline_detail : '';

            $subject_default         = esc_html__('Project hourly request declined', 'taskbot-hourly-addon'); //default email subject
            $contact_default         = wp_kses(
                __('Oho! A project hourly request has been declined by {{buyer_name}} with the reason of <br/> {{decline_detail}} <br />Please click on the button below to view the decline details.<br />{{project_link}}', 'taskbot-hourly-addon'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject            = !empty($taskbot_settings['hourly_request_decline_seller_email_subject']) ? $taskbot_settings['hourly_request_decline_seller_email_subject'] : $subject_default; //getting subject
            $email_content      = !empty($taskbot_settings['hourly_request_decline_seller_email_content']) ? $taskbot_settings['hourly_request_decline_seller_email_content'] : $contact_default; //getting content

            $project_link      = $this->process_email_links($project_link, $project_title); //task/post link

            $email_content = str_replace("{{decline_detail}}", $decline_detail, $email_content);
            $email_content = str_replace("{{seller_name}}", $seller_name, $email_content);
            $email_content = str_replace("{{buyer_name}}", $buyer_name, $email_content);
            $email_content = str_replace("{{project_title}}", $project_title, $email_content);
            $email_content = str_replace("{{project_link}}", $project_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']    = 'seller_name';
            $greeting['greet_value']      = $seller_name;
            $greeting['greet_option_key'] = 'hourly_request_decline_seller_email_greeting';

            $body   = $this->taskbot_email_body($email_content, $greeting);
            $body   = apply_filters('taskbot_seller_project_hourly_request_declined_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }
    }
    new HourlyAddonEmails();
}
