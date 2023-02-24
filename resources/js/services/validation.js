
import { extend } from "vee-validate";
import {email, min, required,max,confirmed, image,size} from 'vee-validate/dist/rules';
extend('required',{
    ...required,
    message:"{_field_} can't be blank"
});
extend('email',{
    ...email,
    message:"Email format is invalid"
});
extend('min',{
    ...min,
    message:"{_field_} must have at least {length} characters"
});
extend('max',{
   ...max,
    message:"{_field_} must have at most {length} characters"
});
extend('confirm',{
    ...confirmed,
    message:"Password and Password Confirmation does not match"
});
extend('image',{
    ...image,
    message:"Profile must be an image"
});
extend('size',{
    ...size,
    message:"Profile must not exceed 2MB of file size"
});