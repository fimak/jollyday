<div class="profile" id="u<?php echo $user->id;?>">
    <div class="profile-inner-shadow-top">
        <div class="profile-inner-bg-separator">
            <div class="profile-inner-shadow-bottom">
                <table class="profile-table">
                    <tbody>
                        <tr>
                            <td class="profile-column-left">
                                    <?php $this->renderPartial('theme.views.profile._left', array(
                                            'user' => $user,
                                    ));?>
                            </td>
                            <td class="profile-column-middle">
                                    <?php $this->renderPartial('theme.views.profile._middle', array(
                                            'user' => $user,
                                            'photos' => User::getPhotos($user->id, $user->id_userpic),
                                    ));?>                 
                            </td>
                            <td class="profile-column-right">
                                    <?php
                                            $this->renderPartial('theme.views.profile._right',array(
                                                    'userMethods' => $user->meetmethodIds,
                                                    'listMethods' => JMeetmethod::getData(),
                                                    'userID' => $user->id,
                                            ));
                                    ?>               
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>          
    </div>

    <div class="gift-container clearfix">
        <?php $this->renderPartial('theme.views.profile._gifts', array(
                'gifts' => $gifts,
                'userID' => $user->id,
        )) ?>
    </div>  
</div>