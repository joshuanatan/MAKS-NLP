<?php if($this->session->status_login == "success"):?>
<div class = "alert alert-success">
    <?php echo $this->session->msg_login;?>
</div>
<?php elseif($this->session->status_login == "error"):?>
<div class = "alert alert-danger">
    <?php echo $this->session->msg_login;?>
</div>
<?php endif;?>
<h2>WELCOME TO <i>Wit.ai</i> ADMINISTRATIVE PAGE</h2>
<br/>
<h3>Quick Brief</h3>
<hr/>
<h4>The main purpose of this module is to manage the interaction between the application with the <i>Natural Language Processing</i> 3<sup>rd</sup> party application</h4>
<h5><i>Wit.ai</i> is one of the avaiable <i>Natural Language Processing System</i>. Wit.ai is free. Using <i>Github acoount / Facebook account</i> for authentication</h5>
<h5><i>Wit.ai</i> provides several endpoints to build the <i>Natural Language Processing System</i>. Endpoints can be invoked by using <i>HTTP</i> request</h5>
<br/>
<h4>Secondary purpose is to manage interaction with another module in the system. There will be other module that will be taking some data from this module</h4>
<h5>This module provides connection by opening endpoints and will be invoked via <i>HTTP</i> request</h5>
<br/>
<br/>