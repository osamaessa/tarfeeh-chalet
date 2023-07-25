<?php 

namespace App\Constant;

class Messages{

    //general
    const GENERAL_ERROR = 'SOMETHING_WENT_WRONG';

    //profile
    const FCM_UPDATED_SUCCESS = 'FCM_UPDATED_SUCCESS';
    const NAME_UPDATED_SUCCESS = 'NAME_UPDATED_SUCCESS';
    const PHONE_UPDATED_SUCCESS = 'PHONE_UPDATED_SUCCESS';
    const ABOUT_UPDATED_SUCCESS = 'ABOUT_UPDATED_SUCCESS';
    const IMAGE_UPDATED_SUCCESS = 'IMAGE_UPDATED_SUCCESS';
    const PASSWORD_UPDATED_SUCCESS = 'PASSWORD_UPDATED_SUCCESS';
    const ADDRESS_UPDATED_SUCCESS = 'ADDRESS_UPDATED_SUCCESS';
    const USER_NOT_FOUND = 'USER_NOT_FOUND';
    const USER_ID_REQUIRED = 'USER_ID_REQUIRED';
  
    //auth
    const INVALID_CRED = 'INVALID_CRED';
    const VERIFY_CODE_WRONG = 'VERIFY_CODE_WRONG';
    const CODE_SEND_SUCCESS = 'CODE_SEND_SUCCESS';
    const PASSWORD_RESET_SUCCESS = 'PASSWORD_RESET_SUCCESS';
    const USER_REGISTERED_SUCCESS = 'USER_REGISTERED_SUCCESS';
    const USER_VERIFIED_SUCCESS = 'USER_VERIFIED_SUCCESS';
    const USER_ALREADY_REGISTERED = 'USER_ALREADY_REGISTERED';
    const USER_UNAUTHORIZED = 'USER_UNAUTHORIZED';
    const USER_NOT_VERIFIED = 'USER_NOT_VERIFIED';
    const PASSWORD_WRONG = 'PASSWORD_WRONG';
    const USER_BLOCKED = 'USER_BLOCKED';
    const USER_FORGET_PASSWORD_VERIFIED_SUCCESS = 'USER_FORGET_PASSWORD_VERIFIED_SUCCESS';

    //country
    const COUNTRY_NOT_FOUND = 'COUNTRY_NOT_FOUND';
    const CITY_NOT_FOUND = 'CITY_NOT_FOUND';
    const CITY_NOT_BELONGS_TO_COUNTRY = 'CITY_NOT_BELONGS_TO_COUNTRY';
    const COUNTRY_ADDED_SUCCESS = 'COUNTRY_ADDED_SUCCESS';
    const COUNTRY_UPDATED_SUCCESS = 'COUNTRY_UPDATED_SUCCESS';
    const COUNTRY_ID_REQUIRED = 'COUNTRY_ID_REQUIRED';

    //image
    const IMAGE_NOT_FOUND = 'IMAGE_NOT_FOUND';
    const IMAGE_WRONG_EXTENSIONS = 'IMAGE_WRONG_EXTENSIONS';
    const IMAGE_ADDED_SUCCESS = 'IMAGE_ADDED_SUCCESS';
    const CHALET_IMAGE_ALREADY_EXIST = 'CHALET_IMAGE_ALREADY_EXIST';
    const IMAGE_DELETED_SUCCESS = 'IMAGE_DELETED_SUCCESS';
    const IMAGES_MAXIMUM_6 = 'IMAGES_MAXIMUM_6';
   
    //chalet
    const CHALET_ID_REQUIRED = 'CHALET_ID_REQUIRED';
    const CHALET_NOT_FOUND = 'CHALET_NOT_FOUND';
    const CHALET_PRICING_NOT_FOUND = 'CHALET_PRICING_NOT_FOUND';
    const CHALET_NOT_READY = 'CHALET_NOT_READY';
    const CHALET_ALREADY_EXIST = 'CHALET_ALREADY_EXIST';
    const CHALET_BLOCKED = 'CHALET_BLOCKED';
    const CHALET_ALREADY_APPROVED = 'CHALET_ALREADY_APPROVED';
    const CHALET_IMAGE_NOT_FOUND = 'CHALET_IMAGE_NOT_FOUND';
    const CHALET_LICENSE_IMAGE_NOT_FOUND = 'CHALET_LICENSE_IMAGE_NOT_FOUND';
    const CHALET_USER_ID_CARD_IMAGE_NOT_FOUND = 'CHALET_USER_ID_CARD_IMAGE_NOT_FOUND';
    const CHALET_ADDRESS_NOT_FOUND = 'CHALET_ADDRESS_NOT_FOUND';
    const CHALET_PHONE_NOT_FOUND = 'CHALET_PHONE_NOT_FOUND';

    //feature
    const FEATURE_NOT_FOUND = 'FEATURE_NOT_FOUND';
    const CHALET_FEATURE_ALREADY_EXIST = 'CHALET_FEATURE_ALREADY_EXIST';

    //payment
    const BALANCE_NOT_ENOUGH = 'BALANCE_NOT_ENOUGH';
    const PAYMENT_ADDED_SUCCESS = 'PAYMENT_ADDED_SUCCESS';

    //review
    const REVIEW_ADDED_SUCCESS = 'REVIEW_ADDED_SUCCESS';
    const REVIEW_DELETED_SUCCESS = 'REVIEW_DELETED_SUCCESS';
    const REVIEW_ID_REQUIRED = 'REVIEW_ID_REQUIRED';
    const REVIEW_NOT_FOUND = 'REVIEW_NOT_FOUND';
    const REVIEW_ALREADY_ADDED = 'REVIEW_ALREADY_ADDED';
    
    //report
    const REPORT_ADDED_SUCCESS = 'REPORT_ADDED_SUCCESS';
    const REPORT_DELETED_SUCCESS = 'REPORT_DELETED_SUCCESS';
    const REPORT_NOT_FOUND = 'REPORT_NOT_FOUND';
 
    //notification
    const NOTIFICATION_SENT_SUCCESS = 'NOTIFICATION_SENT_SUCCESS';   
  
}
?>