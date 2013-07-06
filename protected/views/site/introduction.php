<script type="text/javascript" src="http://cdn.dev.skype.com/uri/skype-uri.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/js/introduction.js'></script> 

<div class="row">
    <div class="row">
        <h2><span class="introduction" id="about">About</span><h2>
            <hr/>
            <div id="about_content">
                <p>
                    <span class="primary label">FENT</span> (Framgia dEvice maNagement sysTem) is the Web Service using
                    for managing Framgia's devices. 
                </p>
                <p>
                    <span class="primary label">FENT</span> is made by <span class="secondary label">Framgia PHP Team</span> 
                    It is written in PHP, using <a href="http://www.yiiframework.com/">Yii Framework</a> 
                </p>  
            </div>
    </div>
    <div class="row">
        <h2><span class="introduction" id="features">Features</span><h2>        
        <hr/>
        <div id="features_content">
            <h3>
                By using <span class="primary label">FENT</span> you can :
            </h3>        
            <p><i class="icon-check"></i>Send request to borrow devices</p>
            <p><i class="icon-check"></i>Keep track of the devices that you like (by adding them to your favorite list)</p>
            <p><i class="icon-check"></i>
                View current status of a device (whether it is <span class="success label">available</span> (which means it is working well) or 
                <span class="danger label">unavailable</span> (which means, for some reasons, the device is temporarily broken down or can not be used), 
                the person who is borrowing it, list of people who sent requests to borrow it ...)</p>
            <p><i class="icon-check"></i>
                View status of your request (<span class="info label">rejected</span>, 
                <span class="danger label">expired</span> (which means it's time to return the device), 
                <span class="success label">un-expired</span> or <span class="warning label">finished</span>)
            </p>
            <p><i class="icon-check"></i>
                Search for people (by name or employee code) or devices (by device name, device serial number, device management number).
                All you have to do is just typing <span class="success label">Ctrl + S</span> (or <span class="success label">Cmd + S</span> in Mac) and start searching.
                <span class="primary label">FENT</span> implements a quite effective search engine that allows you to search for 
                people and devices but do not require typing the name exactly. In some cases (just in some cases!), even if you mistype the person or device name,
                the results are also found. But do not put too much expectation in <span class="primary label">FENT</span>, it is not Google!
            </p>
            <p><i class="icon-check"></i>
                <span class="primary label">FENT</span> has a Real Time Notification System, so after your request 
                is accepted or rejected by the admin, you will be notified IMMEDIATELY.
            </p>
            <p><i class="icon-check"></i>
                And more ...
            </p>
        </div>
    </div>
    <div class="row">
        <h2><span class="introduction" id="contact">Contact</span><h2>                     
        <hr/>
        <div id="contact_content">
            <p style="display: inline">
                <span style="float:left"><span class="primary label">FENT</span> was developed in a very short time and
                it has not been tested carefully yet.
                <br/>If you have any ideas or found any bugs, please feel free to contact me.  
                You can <a href="mailto:tran.duc.thang@framgia.com">send me an email</a>. </span> 
                <br/>
                <span style="float:left">Or you can drop me a message via</span>
                <span id="SkypeButton_Chat_tieuquetu_1" style="float:left; margin-top:-7px; margin-left:-7px">
                    <script type="text/javascript">
                        Skype.ui({   
                            "name": "chat",
                            "element": "SkypeButton_Chat_tieuquetu_1",
                            "participants": ["tieuquetu"],
                            "imageSize": 16
                        });
                    </script>
                </span>
            </p>
        </div>
    </div>
</div>

