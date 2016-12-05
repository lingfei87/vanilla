<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php
    if ($this->data('User')) {
        echo t('Edit User');
    } else {
        echo t('Add User');
    }
    ?></h1>
<?php
echo $this->Form->open(array('class' => 'User', 'autocomplete' => 'off'));
echo $this->Form->errors();

if ($this->data('AllowEditing')) { ?>
    <ul>
        <li class="form-group">
            <div class="label-wrap">
                <?php echo $this->Form->label('Username', 'Name'); ?>
            </div>
            <div class="input-wrap">
                <?php echo $this->Form->textBox('Name', ['autocomplete' => 'off']); ?>
            </div>
        </li>
        <li class="form-group">
            <div class="label-wrap">
                <?php echo $this->Form->label('Email', 'Email');
                if (UserModel::noEmail()) {
                    echo '<div class="input">',
                    t('Email addresses are disabled.', 'Email addresses are disabled. You can only add an email address if you are an administrator.'),
                    '</div>';
                } ?>
            </div>
            <div class="input-wrap">
                <?php
                $EmailAttributes = ['autocomplete' => 'off'];

                // Email confirmation
                if (!$this->data('_EmailConfirmed')) {
                    $EmailAttributes['class'] = 'Unconfirmed';
                }

                echo $this->Form->textBox('Email', $EmailAttributes); ?>
            </div>
        </li>

        <?php if ($this->data('_CanConfirmEmail')): ?>
            <li class="User-ConfirmEmail form-group">
                <div class="input-wrap no-label">
                    <?php echo $this->Form->checkBox('ConfirmEmail', t("Email is confirmed"), array('value' => '1')); ?>
                </div>
            </li>
        <?php endif ?>
        <li class="form-group">
            <div class="input-wrap no-label">
                <?php echo $this->Form->checkBox('ShowEmail', t('Email visible to other users'), array('value' => '1')); ?>
            </div>
        </li>
        <li class="form-group">
            <div class="input-wrap no-label">
                <?php echo $this->Form->checkBox('Verified', t('Verified Label', 'Verified. Bypasses spam and pre-moderation filters.'), array('value' => '1')); ?>
            </div>
        </li>
        <li class="form-group">
            <div class="input-wrap no-label">
                <?php echo $this->Form->checkBox('Banned', t('Banned'), array('value' => $this->data('BanFlag'))); ?>
                <?php if ($this->data('BannedOtherReasons')): ?>
                <div class="text-danger info"><?php echo t(
                        'This user is also banned for other reasons and may stay banned.',
                        'This user is also banned for other reasons and may stay banned or become banned again.'
                    )?></div>
                <?php endif; ?>
            </div>
        </li>

        <?php if (c('Garden.Profile.Locations', false)) : ?>
            <li class="form-group User-Location">
                <div class="label-wrap">
                    <?php echo $this->Form->label('Location', 'Location'); ?>
                </div>
                <div class="input-wrap">
                    <?php echo $this->Form->textBox('Location', ['autocomplete' => 'off']); ?>
                </div>
            </li>
        <?php endif; ?>

        <?php if (c('Garden.Profile.Titles', false)) : ?>
            <li class="form-group User-Title">
                <div class="label-wrap">
                    <?php echo $this->Form->label('Title', 'Title'); ?>
                </div>
                <div class="input-wrap">
                    <?php echo $this->Form->textBox('Title', ['autocomplete' => 'off']); ?>
                </div>
            </li>
        <?php endif; ?>

        <?php
        $this->fireEvent('CustomUserFields')
        ?>

    <?php if (count($this->data('Roles'))) : ?>
        <li class="form-group">
            <div class="label-wrap">
                <?php echo t('Check all roles that apply to this user:'); ?>
            </div>
            <div class="input-wrap">
                <?php echo $this->Form->checkBoxList("RoleID", array_flip($this->data('Roles')), array_flip($this->data('UserRoles'))); ?>
            </div>
        </li>
    <?php endif; ?>
        <li class="PasswordOptions form-group">
            <div class="label-wrap">
                <?php echo t('Password Options'); ?>
            </div>
            <div class="input-wrap">
                <?php echo $this->Form->radioList('ResetPassword', $this->ResetOptions); ?>
            </div>
        </li>
        <?php if (array_key_exists('Manual', $this->ResetOptions)) : ?>
            <li id="NewPassword">
                <div class="form-group">
                    <div class="label-wrap">
                        <?php echo $this->Form->label('New Password', 'NewPassword'); ?>
                    </div>
                    <div class="input-wrap">
                        <?php echo $this->Form->input('NewPassword', 'password'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="buttons input-wrap no-label">
                        <?php
                        echo anchor(t('Generate Password'), '#', 'GeneratePassword btn btn-secondary');
                        echo anchor(t('Reveal Password'), '#', 'RevealPassword btn btn-secondary',
                            ['data-hide-text' => t('Hide Password'), 'data-show-text' => t('Reveal Password')]);
                        ?>
                    </div>
                </div>
            </li>
        <?php endif; ?>
    </ul>
    <?php

    $this->fireEvent('AfterFormInputs');
    echo $this->Form->close('Save');
}
