<?php

class DiagramController
{
    public function tableAccessedAction(Request $request, $params = [])
    {
        $params['entities'] = EmailsAccessedEntity::findAllByRange('2016-05-09 17:42:24', '2016-05-09 18:02:26');

        return ['template' => 'partial_table_accessed',
            'params' => $params
        ];
    }

    public function tableSendedAction(Request $request, $params = [])
    {
        $params['entities'] = EmailsSendedEntity::findAllByRange('2016-05-09 17:42:24', '2016-05-09 18:02:26');

        return ['template' => 'partial_table_sended',
            'params' => $params
        ];
    }
    
    public function tableUnsubscribedAction(Request $request, $params = [])
    {
        $params['entities'] = EmailsUnsubscribedEntity::findAllByRange('2016-05-09 17:42:24', '2016-05-09 18:02:26');

        return ['template' => 'partial_table_unsubscribed',
            'params' => $params
        ];
    }
}