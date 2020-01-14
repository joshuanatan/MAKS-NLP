<?php
$where = array(
    "status_aktif_wit_ai_acc" => 1
);
$wit_registered_account = isExistsInTable("tbl_wit_ai_acc",$where);

?>
<div class="site-menubar">
    <div class="site-menubar-header">
        <div class="cover overlay">
            <div class="overlay-panel vertical-align overlay-background">
                <div class="vertical-align-middle">
                    <a class="avatar avatar-lg" href="javascript:void(0)">
                        <img src="<?php echo base_url();?>assets/images/default.jpg" alt="...">
                    </a>
                    <div class="site-menubar-info">
                        <h5 class="site-menubar-user"><?php echo $this->session->nama_user;?></h5>
                        <p class="site-menubar-email"><?php echo $this->session->email_user;?> </p>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-item">
                        <a href="<?php echo base_url();?>user">
                            <i class="site-menu-icon wb-memory" aria-hidden="true"></i>
                            <span class="site-menu-title">User Administrator</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="<?php echo base_url();?>token">
                            <i class="site-menu-icon wb-check-circle" aria-hidden="true"></i>
                            <span class="site-menu-title">Tokenization</span>
                        </a>
                    </li>
                    <li class="site-menu-item">
                        <a href="<?php echo base_url();?>setup">
                            <i class="site-menu-icon wb-wrench" aria-hidden="true"></i>
                            <span class="site-menu-title">Wit.ai Configuration</span>
                        </a>
                    </li>
                    <li class="site-menu-item has-sub">
                        <?php if($wit_registered_account == 1):?>
                        <a href="javascript:alert('Dont Have Any Active Wit.ai Account');window.location = '<?php echo base_url();?>setup'">
                            <i class="site-menu-icon wb-library" aria-hidden="true"></i>
                            <span class="site-menu-title">Activate Wit.ai Account</span>
                        </a>
                        <?php else:?>
                        <a href="javascript:void(0);">
                            <i class="site-menu-icon wb-library" aria-hidden="true"></i>
                            <span class="site-menu-title">Wit.ai Functions</span>
                            <span class="site-menu-arrow"></span>
                        </a>
                        <ul class="site-menu-sub">
                            <?php /*koneksi database karena mau bkin multiple database */?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?php echo base_url();?>function/entity">
                                    <span class="site-menu-title">Entity</span>
                                </a>
                            </li>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?php echo base_url();?>function/intent">
                                    <span class="site-menu-title">Intent</span>
                                </a>
                            </li>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?php echo base_url();?>function/samples">
                                    <span class="site-menu-title">Samples</span>
                                </a>
                            </li>
                        </ul>
                        <?php endif;?>
                    </li>
                </ul> 
            </div>
        </div>
    </div>
</div>