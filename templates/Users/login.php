<div class="row">
    <aside class="column">
        <div class="side-nav">
            <!-- Commenting out the side heading for now-->
            <!--<h4 class="heading"></h4>-->
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="users form content">
            <h2>Login</h2>
            <?= $this->Form->create() ?>
            <fieldset>    
                <?php
                    echo $this->Form->control('email');
                    echo $this->Form->control('password', ['type' => 'password']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Login')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>