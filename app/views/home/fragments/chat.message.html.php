<div <?=($chatMsg->getSenderId() == $me->getUserId())?'class="d-flex align-items-center text-right justify-content-end msg-right"':'class="d-flex align-items-center msg-left"'?>>
    <div <?=($chatMsg->getSenderId()==$me->getUserId())? 'class="pr-2"':'class="pr-2 pl-1"'?>> <span class="name">@<?=($chatMsg->getSenderId()==$me->getUserId())? $me->getName() : $peer->getName()?></span>
        <p class="msg"><?=$chatMsg->getMsg()?></p>
    </div>
</div>