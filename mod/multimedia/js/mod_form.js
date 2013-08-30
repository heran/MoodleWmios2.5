M.mod_multimedia = M.mod_multimedia || {};

/**
* When pick one multimedia,update the content field.
* Use base64_encode 
* 
*/
M.mod_multimedia.init_filepicker = function(Y,elementname){
    Y.use('event,node',function(){
        Y.one('#id_'+elementname).on('change',function(e){
            //var client_id = Y.one(e.target).ancestor('.ffilepicker')
            //.one('.filepicker-filelist').get('id').replace('file_info_','');
            //var client = M.core_filepicker.instances[client_id];
            var url = Y.one(e.target).ancestor('.ffilepicker').one('.filepicker-filename>a').get('href');
            var result = url.match(new RegExp("(.+)\#(.+)", "i"));
            var content = M.mod_multimedia.Base64.encode('<a href="'+result[1]+'">'+result[2]+'</a>');
            Y.one(e.target).ancestor('form').one('input[name=content]').set('value',content);
        })
    });
};

/**
* When load the form page, update the file picker with the content.
* Use base64_decode.
* 
*/
M.mod_multimedia.restore_filepicker = function(Y,elementname){
    Y.use('event,node',function(){
        var content = Y.one('input[name=content]').get('value');
        if(!content)return;
        try{
            content = M.mod_multimedia.Base64.decode(content);
            var html = content + '<div class="dndupload-progressbars"></div>';
            Y.one('#id_'+elementname).ancestor('.ffilepicker').one('.filepicker-filename').setContent(html);
        }catch(e){
        }
    });
};

//copy from http://yui.yahooapis.com/combo?gallery-2013.02.27-21-03/build/gallery-base64/gallery-base64.js
//This isn't in the moodle yui
(function(Y) {

    /*
    * Copyright (c) 2009 Nicholas C. Zakas. All rights reserved.
    * http://www.nczonline.net/
    */

    /**
    * Base64 encoder/decoder
    * @module gallery-base64
    */

    //base 64 digits
    var digits = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

    /**
    * Base64 encoder/decoder
    * @class Base64
    * @static
    */
    Y.Base64 = {

        /**
        * Base64-decodes a string of text.
        * @param {String} text The text to decode.
        * @return {String} The base64-decoded string.
        */    
        decode: function(text){

            //ignore white space
            text = text.replace(/\s/g,"");

            //first check for any unexpected input
            if(!(/^[a-z0-9\+\/\s]+\={0,2}$/i.test(text)) || text.length % 4 > 0){
                throw new Error("Not a base64-encoded string.");
            }    

            //local variables
            var cur, prev, digitNum,
            i=0,
            result = [];

            //remove any equals signs
            text = text.replace(/\=/g, "");

            //loop over each character
            while(i < text.length){

                cur = digits.indexOf(text.charAt(i));
                digitNum = i % 4;

                switch(digitNum){

                    //case 0: first digit - do nothing, not enough info to work with

                    case 1: //second digit
                        result.push(String.fromCharCode(prev << 2 | cur >> 4));
                        break;

                    case 2: //third digit
                        result.push(String.fromCharCode((prev & 0x0f) << 4 | cur >> 2));
                        break;

                    case 3: //fourth digit
                        result.push(String.fromCharCode((prev & 3) << 6 | cur));
                        break;
                }

                prev = cur;
                i++;
            }

            //return a string
            return result.join("");    

        },


        /**
        * Base64-encodes a string of text.
        * @param {String} text The text to encode.
        * @return {String} The base64-encoded string.
        */
        encode: function(text){

            if (/([^\u0000-\u00ff])/.test(text)){
                throw new Error("Can't base64 encode non-ASCII characters.");
            }   

            var i = 0,
            cur, prev, byteNum,
            result=[];      

            while(i < text.length){

                cur = text.charCodeAt(i);
                byteNum = i % 3;

                switch(byteNum){
                    case 0: //first byte
                        result.push(digits.charAt(cur >> 2));
                        break;

                    case 1: //second byte
                        result.push(digits.charAt((prev & 3) << 4 | (cur >> 4)));
                        break;

                    case 2: //third byte
                        result.push(digits.charAt((prev & 0x0f) << 2 | (cur >> 6)));
                        result.push(digits.charAt(cur & 0x3f));
                        break;
                }

                prev = cur;
                i++;
            }

            if (byteNum == 0){
                result.push(digits.charAt((prev & 3) << 4));
                result.push("==");
            } else if (byteNum == 1){
                result.push(digits.charAt((prev & 0x0f) << 2));
                result.push("=");
            }

            return result.join("");

        }

    };


})(M.mod_multimedia);