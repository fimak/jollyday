<div class="profile" id="u<?php echo $user->id;?>">
    <div class="profile-inner-shadow-top">
        <div class="profile-inner-bg-separator">
            <div class="profile-inner-shadow-bottom">
                <table class="profile-table">
                    <tbody>
                        <tr>
                            <td class="profile-column-left">
                                    <?php $this->renderPartial('theme.views.app.profile._left', array(
                                            'user' => $user,
                                            'profileType' => $profileType,
                                    ));?>
                            </td>
                            <td class="profile-column-middle">
                                    <?php $this->renderPartial('theme.views.app.profile._middle', array(
                                            'user' => $user,
                                            'profileType' => $profileType,
                                            'photos' => User::getPhotos($user->id, $user->id_userpic),
                                    ));?>                 
                            </td>
                            <td class="profile-column-right">
                                <?php
                                        switch($profileType)
                                        {
                                                case 'own' :
                                                        $this->renderPartial('theme.views.app.profile._right_own',array(
                                                               'userMethods' => $user->meetmethodIds,
                                                               'listMethods' => JMeetmethod::getData(),
                                                               'profileType' => $profileType,
                                                        ));
                                                        break;
                                                case 'search' :
                                                        if($user->id == Yii::app()->user->id)
                                                        {
                                                                $this->renderPartial('theme.views.app.profile._right_own',array(
                                                                       'userMethods' => $user->meetmethodIds,
                                                                       'listMethods' => JMeetmethod::getData(),
                                                                       'profileType' => $profileType,
                                                                ));
                                                                break;     
                                                        }
                                                        elseif($blacklistStatus = Blacklist::getBlacklistStatus(Yii::app()->user->id, $user->id))
                                                        {
                                                                $this->renderPartial('theme.views.app.profile._right_blacklisted',array(
                                                                       'userID' => $user->id,
                                                                       'blacklistStatus' => $blacklistStatus,
                                                                ));
                                                                break;         
                                                        }
                                                        elseif($offerData = Offer::getOfferData($user->id, Yii::app()->user->id))
                                                        {      
                                                                if($offerData['status'] == Offer::ACCEPTED)
                                                                {
                                                                        $offer = Offer::model()->findByPk($offerData['id']);
                                                                    
                                                                        $this->renderPartial('theme.views.app.profile._right_messages',array(
                                                                               'offer' => $offer,
                                                                               'profileType' => $profileType,
                                                                        ));
                                                                }
                                                                else 
                                                                {
                                                                        $this->renderPartial('theme.views.app.profile._right_offered',array(
                                                                                'method' => JMeetmethod::getItem($offerData['id_method']),
                                                                                'userID' => $user->id,
                                                                                'profileType' => $profileType,
                                                                                'offerData' => Offer::getOfferData($user->id, Yii::app()->user->id),
                                                                        ));
                                                                }
                                                                break;
                                                        }
                                                        else
                                                        {    
                                                                $this->renderPartial('theme.views.app.profile._right_uncontacted',array(
                                                                        'userMethods' => $user->meetmethodIds,
                                                                        'listMethods' => JMeetmethod::getData(),
                                                                        'userID' => $user->id,
                                                                        'profileType' => $profileType,
                                                                ));
                                                        }
                                                        break;
                                                case 'message' :
                                                        $this->renderPartial('theme.views.app.profile._right_messages',array(
                                                               'offer' => $offer,
                                                               'profileType' => $profileType,
                                                        ));
                                                        break;
                                                case 'blacklist' :
                                                        $this->renderPartial('theme.views.app.profile._right_blacklisted',array(
                                                               'userID' => $user->id,
                                                               'blacklistStatus' => Blacklist::getBlacklistStatus(Yii::app()->user->id, $user->id),
                                                        ));
                                                        break;

                                        }
                                ?>               
                            </td>
                        </tr>
                        <?php if($profileType == 'own') : ?>   
                            <tr id="profile-button-wrapper">
                                <td>                
                                    <?php echo CHtml::tag('button', array(
                                            'class' => 'trLoadQuestionaryUpdate button-round azure', 
                                            'data-link' => J::url('questionary/update')
                                    ),'Редактировать анкету');?>                                       
                                </td>
                                <td>
                                    <?php echo CHtml::tag('button', array(
                                        'id' => 'editalbum',
                                        'class' => 'trLoadPhotoAlbum button-round azure', 
                                        'data-link' => J::url('photo/album')
                                    ),'Редактировать альбом');?><!--  
                                    --><?php echo CHtml::tag('button', array(
                                        'id' => 'addphoto',
                                        'class' => 'button-round azure', 
                                        'data-link' => J::url('photo/uploader'),
                                        'onClick' => 'window.location.href = $(this).data("link")',
                                    ),'Добавить фото');?>  

                                </td>
                                <td>
                                    <?php echo CHtml::tag('button', array(
                                        'class' => 'trLoadMethodsForm button-round azure', 
                                        'data-link' => J::url('profile/updatemethods')
                                    ),'Редактировать интересы');?> 
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>          
    </div>

    <div class="gift-container clearfix">
        <?php $this->renderPartial('theme.views.app.gift._gifts', array(
                'gifts' => $gifts,
                'profileType' => $user->id == Yii::app()->user->id ? 'own' : $profileType,
                'userID' => $user->id,
        )) ?>
    </div>  
</div>