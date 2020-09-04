<?php

namespace hybridmind\user_reports_stats\migrations;

class install_user_reports_stats extends \phpbb\db\migration\migration 
{
    
    
    static public function depends_on() 
    {
        return array(
            '\phpbb\db\migration\data\v310\dev'
        );
    }
    
    
    public function update_schema()
    {
        return array(
            'add_tables' => array(
                'phpbb_reports_saver' => array(
                    'COLUMNS' => array(
                        'id' => array(
                            'UINT',
                            NULL,
                            'auto_increment'
                        ),
                        'user_id' => array(
                            'VCHAR:255',
                            ''
                        ),
                        'report_id' => array(
                            'VCHAR:255',
                            ''
                        )
                    ),
                    'PRIMARY_KEY' => 'id'
                )
            )
        );
    }
    
    
    
    
}