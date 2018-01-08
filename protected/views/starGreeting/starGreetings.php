<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php Yii::app()->getClientScript()->registerCssFile("/css/starGreetings/starGreetings.css"); ?>
    <title>Document</title>
</head>
<body>
<div>
    <div class="star-greetings">
        <span class="greetings-tips">预览明星问候</span>
        <Icon type="close" class="close-greetings"/>
    </div>
    <div class="greetings-expand">
        <div class="mask">
            <div class="round round-sm"></div>
            <div class="round round-md"></div>
            <div class="round round-lg"></div>
            <img src="" class="avatar"/>
            <audio src=""></audio>
            <i class="put-away"></i>
        </div>
    </div>
</div>
<script type="text/javascript">
    var greetingsData = {
        "ret": 0,
        "sub": 0,
        "msg": "success",
        "data": {
            "title": "新年问候",
            "bg_img": "<?php echo $bg_img;?>",
            "tips": "<?php echo $tips;?>",
            "star_img": "<?php echo $img_url;?>",
            "voice_url": "<?php echo $voice_path;?>"
        }
    }
</script>
<script type="text/javascript">
    var stopPlay = true;
    var greetingsExp = document.querySelector('.greetings-expand');
    var img = document.querySelector('img');
    var audioNode = document.querySelector('audio');
    var aDiv = document.querySelectorAll('.round');
    var tips = document.querySelector('.greetings-tips');
    var data = greetingsData.data;

    greetingsExp.style.backgroundImage = "url(" + data.bg_img + ")";
    tips.innerHTML = data.tips;
    img.src = data.star_img;
    audioNode.src = data.voice_url;

    img.addEventListener('click', onPlayVoice);

    onPlayVoice();

    function onPlayVoice() {
        if (audioNode) {
            stopPlay = !stopPlay;
            if (!stopPlay) {
                for (var i = 0; i < aDiv.length; i++) {
                    aDiv[i].classList.remove('stop-animate');
                }
                audioNode.play();
                audioNode.onended = function () {
                    stopPlay = true;
                    for (var i = 0; i < aDiv.length; i++) {
                        aDiv[i].classList.add('stop-animate');
                    }
                }
            } else {
                audioNode.pause();
                audioNode.currentTime = 0;
                for (var i = 0; i < aDiv.length; i++) {
                    aDiv[i].classList.add('stop-animate');
                }
            }
        }
    }
</script>
</body>
</html>
