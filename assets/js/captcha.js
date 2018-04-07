function new_captcha(){
    document.getElementById('captcha_img').src = site_url+ '/captcha/index/' + Math.random(); 
}