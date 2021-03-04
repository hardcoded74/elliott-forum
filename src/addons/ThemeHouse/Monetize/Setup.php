<?php

namespace ThemeHouse\Monetize;

use ThemeHouse\Monetize\Repository\Keyword;
use ThemeHouse\Monetize\XF\Repository\UserUpgrade;
use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use XF\Entity\Phrase;

/**
 * Class Setup
 * @package ThemeHouse\Monetize
 */
class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    /**
     *
     */
    public function installStep1()
    {
        $schemaManager = $this->schemaManager();

        foreach ($this->getTables() as $tableName => $closure) {
            $schemaManager->createTable($tableName, $closure);
        }
    }

    /**
     * @return array
     */
    public function getTables()
    {
        $tables = [];

        $tables['xf_th_monetize_affiliate_link'] = function (Create $table) {
            $table->addColumn('affiliate_link_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('reference_link_prefix', 'text');
            $table->addColumn('reference_link_suffix', 'text');
            $table->addColumn('reference_link_parser', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('url_cloaking', 'boolean')->setDefault(0);
            $table->addColumn('url_encoding', 'boolean')->setDefault(0);
            $table->addColumn('link_criteria', 'mediumblob');
            $table->addColumn('user_criteria', 'mediumblob');
        };

        $tables['xf_th_monetize_keyword'] = function (Create $table) {
            $table->addColumn('keyword_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('keyword', 'varchar(250)');
            $table->addColumn('keyword_options', 'blob');
            $table->addColumn('replace_type', 'enum')->values(['url', 'html']);
            $table->addColumn('replacement', 'blob');
            $table->addColumn('limit', 'int');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('user_criteria', 'mediumblob');
        };

        $tables['xf_th_monetize_sponsor'] = function (Create $table) {
            $table->addColumn('th_sponsor_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('url', 'varchar(250)')->setDefault('');
            $table->addColumn('image', 'varchar(250)')->setDefault('');
            $table->addColumn('width', 'int')->setDefault(0);
            $table->addColumn('height', 'int')->setDefault(0);
            $table->addColumn('directory', 'boolean')->setDefault(1);
        };

        $tables['xf_th_monetize_upgrade_page'] = function (Create $table) {
            $table->addColumn('upgrade_page_id', 'int')->autoIncrement();
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('display_order', 'int');
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('page_criteria', 'mediumblob');
            $table->addColumn('page_criteria_overlay_only', 'boolean')->setDefault(1);
            $table->addColumn('show_all', 'boolean')->setDefault(1);
            $table->addColumn('overlay', 'boolean')->setDefault(0);
            $table->addColumn('overlay_dismissible', 'boolean')->setDefault(0);
            $table->addColumn('accounts_page', 'boolean')->setDefault(1);
            $table->addColumn('error_message', 'boolean')->setDefault(1);
            $table->addColumn('upgrade_page_links', 'blob');
            $table->addColumn('accounts_page_link', 'boolean')->setDefault(1);
        };

        $tables['xf_th_monetize_upgrade_page_relation'] = function (Create $table) {
            $table->addColumn('upgrade_page_id', 'int');
            $table->addColumn('user_upgrade_id', 'int');
            $table->addColumn('display_order', 'int');
            $table->addColumn('featured', 'boolean')->setDefault(0);
            $table->addUniqueKey(['upgrade_page_id', 'user_upgrade_id']);
        };

        $tables['xf_th_monetize_alert'] = function (Create $table) {
            $table->addColumn('alert_id', 'int')->autoIncrement();
            $table->addColumn('send_rules', 'blob');
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('user_id', 'int');
            $table->addColumn('link_url', 'varchar(250)');
            $table->addColumn('link_title', 'varchar(250)');
            $table->addColumn('body', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('user_upgrade_criteria', 'mediumblob');
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('next_send', 'int')->setDefault(0);
            $table->addColumn('limit_alerts', 'int')->setDefault(0);
            $table->addColumn('limit_days', 'int')->setDefault(0);
        };

        $tables['xf_th_monetize_email'] = function (Create $table) {
            $table->addColumn('email_id', 'int')->autoIncrement();
            $table->addColumn('send_rules', 'blob');
            $table->addColumn('from_name', 'varchar(250)');
            $table->addColumn('from_email', 'varchar(250)');
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('format', 'enum')->values(['', 'html']);
            $table->addColumn('body', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('wrapped', 'boolean')->setDefault(1);
            $table->addColumn('unsub', 'boolean')->setDefault(1);
            $table->addColumn('receive_admin_email_only', 'boolean')->setDefault(1);
            $table->addColumn('user_upgrade_criteria', 'mediumblob');
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('next_send', 'int')->setDefault(0);
            $table->addColumn('limit_emails', 'int')->setDefault(0);
            $table->addColumn('limit_days', 'int')->setDefault(0);
        };

        $tables['xf_th_monetize_message'] = function (Create $table) {
            $table->addColumn('message_id', 'int')->autoIncrement();
            $table->addColumn('send_rules', 'blob');
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('user_id', 'int');
            $table->addColumn('body', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('open_invite', 'boolean')->setDefault(1);
            $table->addColumn('conversation_locked', 'boolean')->setDefault(1);
            $table->addColumn('delete_type', 'enum')->values(['', 'deleted', 'deleted_ignored']);
            $table->addColumn('user_upgrade_criteria', 'mediumblob');
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('next_send', 'int')->setDefault(0);
            $table->addColumn('limit_messages', 'int')->setDefault(0);
            $table->addColumn('limit_days', 'int')->setDefault(0);
        };

        $tables['xf_th_monetize_alert_log'] = function (Create $table) {
            $table->addColumn('alert_log_id', 'int')->autoIncrement();
            $table->addColumn('log_date', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('alert_id', 'int');
            $table->addKey('log_date');
            $table->addKey('user_id');
            $table->addKey(['user_id', 'log_date']);
            $table->addKey(['user_id', 'alert_id']);
        };

        $tables['xf_th_monetize_email_log'] = function (Create $table) {
            $table->addColumn('email_log_id', 'int')->autoIncrement();
            $table->addColumn('log_date', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('email_id', 'int');
            $table->addKey('log_date');
            $table->addKey('user_id');
            $table->addKey(['user_id', 'log_date']);
            $table->addKey(['user_id', 'email_id']);
        };

        $tables['xf_th_monetize_message_log'] = function (Create $table) {
            $table->addColumn('message_log_id', 'int')->autoIncrement();
            $table->addColumn('log_date', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('message_id', 'int');
            $table->addKey('log_date');
            $table->addKey('user_id');
            $table->addKey(['user_id', 'log_date']);
            $table->addKey(['user_id', 'message_id']);
        };

        return $tables;
    }

    /**
     *
     */
    public function installStep2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_node', function (Alter $table) {
            $table->addColumn('th_sponsor_id', 'int')->setDefault(0);
        });
    }

    /**
     *
     */
    public function installStep3()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user', function (Alter $table) {
            $table->changeColumn('user_state')->addValues(['thmonetize_upgrade']);
        });
    }

    /**
     *
     */
    public function installStep4()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade', function (Alter $table) {
            $table->addColumn('thmonetize_features', 'mediumblob')->nullable();
            $table->addColumn('thmonetize_style_properties', 'mediumblob')->nullable();
            $table->addColumn('thmonetize_custom_amount', 'boolean')->setDefault(0);
            $table->addColumn('thmonetize_allow_multiple', 'boolean')->setDefault(0);
        });
    }

    /**
     *
     */
    public function installStep5()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_profile', function (Alter $table) {
            $table->addColumn('thmonetize_active_upgrades', 'mediumblob')->nullable();
            $table->addColumn('thmonetize_expired_upgrades', 'mediumblob')->nullable();
        });
    }

    public function installStep6()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade_active', function (Alter $table) {
            $table->addColumn('thmonetize_updated', 'int')->setDefault(0);
        });
    }

    public function installStep7()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade_expired', function (Alter $table) {
            $table->addColumn('thmonetize_updated', 'int')->setDefault(0);
        });
    }

    /**
     * @param array $stateChanges
     * @throws \XF\PrintableException
     */
    public function postInstall(array &$stateChanges)
    {
        if ($this->applyDefaultPermissions()) {
            // since we're running this after data imports, we need to trigger a permission rebuild
            // if we changed anything
            $this->app->jobManager()->enqueueUnique(
                'permissionRebuild',
                'XF:PermissionRebuild',
                [],
                false
            );
        }

        $this->createDefaultUpgradePage();
        $this->createDefaultPaymentProvider();
        $this->createDefaultPaymentProfile();
        $this->createDefaultAlert();
        $this->createDefaultEmail();
        $this->createDefaultMessage();

        /** @var UserUpgrade $userUpgradeRepo */
        $userUpgradeRepo = \XF::repository('XF:UserUpgrade');
        $userUpgradeRepo->generateCssForThMonetizeUserUpgrades();
    }

    /**
     * @param null $previousVersion
     * @return bool
     */
    protected function applyDefaultPermissions($previousVersion = null)
    {
        $applied = false;

        if (!$previousVersion || $previousVersion < 1000037) {
            $this->applyGlobalPermission('forum', 'thMonetize_viewPost', 'forum', 'viewContent');
            $this->applyContentPermission('forum', 'thMonetize_viewPost', 'forum', 'viewContent');

            $applied = true;
        }

        return $applied;
    }

    /**
     * @param int $previousVersion
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    protected function createDefaultUpgradePage($previousVersion = 0)
    {
        if ($previousVersion < 1000035) {
            $this->createUpgradePage('Account upgrades');
        }
    }

    /**
     * @param $title
     * @throws \XF\PrintableException
     */
    public function createUpgradePage($title)
    {
        /** @var \ThemeHouse\Monetize\Entity\UpgradePage $upgradePage */
        $upgradePage = $this->app->em()->create('ThemeHouse\Monetize:UpgradePage');
        $success = $upgradePage->save(false);

        if ($success) {
            $masterTitle = $upgradePage->getMasterPhrase();
            $masterTitle->phrase_text = $title;
            $masterTitle->save(false);
        }
    }

    /**
     * @param int $previousVersion
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    protected function createDefaultPaymentProvider($previousVersion = 0)
    {
        if ($previousVersion < 1000037) {
            $this->createPaymentProvider('thmonetize_free', 'ThemeHouse\Monetize:Free');
        }

        if ($previousVersion < 1000133) {
            $this->createPaymentProvider('thmonetize_other', 'ThemeHouse\Monetize:Other');
        }
    }

    /**
     * @param $id
     * @param $class
     * @throws \XF\PrintableException
     */
    public function createPaymentProvider($id, $class)
    {
        /** @var \XF\Entity\PaymentProvider $paymentProvider */
        $paymentProvider = $this->app->em()->create('XF:PaymentProvider');
        $paymentProvider->provider_id = $id;
        $paymentProvider->provider_class = $class;
        $paymentProvider->addon_id = 'ThemeHouse/Monetize';
        $paymentProvider->save(false);
    }

    /**
     * @param int $previousVersion
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    protected function createDefaultPaymentProfile($previousVersion = 0)
    {
        if ($previousVersion < 1000037) {
            $this->createPaymentProfile('thmonetize_free', 'Free');
        }
    }

    /**
     * @param $id
     * @param $title
     * @throws \XF\PrintableException
     */
    public function createPaymentProfile($id, $title)
    {
        /** @var \XF\Entity\PaymentProfile $paymentProfile */
        $paymentProfile = $this->app->em()->create('XF:PaymentProfile');
        $paymentProfile->provider_id = $id;
        $paymentProfile->title = $title;
        $paymentProfile->save(false);
    }

    /**
     * @param int $previousVersion
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    protected function createDefaultAlert($previousVersion = 0)
    {
        if ($previousVersion < 1000041) {
            $this->createAlert(
                'Upgrade purchased',
                [
                    'day_type' => 'dom',
                    'dom' => [-1]
                ],
                'Thank you for purchasing one of our user upgrades.',
                1,
                7,
                [
                    'is_active' => ['rule' => 'is_active'],
                    'is_not_expired' => ['rule' => 'is_not_expired'],
                    'start_date_within' => ['rule' => 'start_date_within', 'data' => ['days' => '1']],
                ]
            );
        }
    }

    /**
     * @param $title
     * @param $sendRules
     * @param $body
     * @param $limitAlerts
     * @param $limitDays
     * @param $userUpgradeCriteria
     * @throws \XF\PrintableException
     */
    public function createAlert($title, $sendRules, $body, $limitAlerts, $limitDays, $userUpgradeCriteria)
    {
        /** @var \ThemeHouse\Monetize\Entity\Alert $alert */
        $alert = $this->app->em()->create('ThemeHouse\Monetize:Alert');
        $alert->title = $title;
        $alert->send_rules = $sendRules;
        $alert->body = $body;
        $alert->limit_alerts = $limitAlerts;
        $alert->limit_days = $limitDays;
        $alert->user_upgrade_criteria = $userUpgradeCriteria;
        $alert->save(false);
    }

    /**
     * @param int $previousVersion
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    protected function createDefaultEmail($previousVersion = 0)
    {
        if ($previousVersion < 1000041) {
            $this->createEmail(
                'Your upgrade has expired',
                [
                    'day_type' => 'dom',
                    'dom' => [-1]
                ],
                "Hi {name},

This is a courtesy message to let you know that one of your user upgrades has just expired.",
                1,
                7,
                [
                    'is_expired' => ['rule' => 'is_expired'],
                    'is_not_active' => ['rule' => 'is_not_active'],
                    'is_not_recurring' => ['rule' => 'is_not_recurring'],
                    'end_date_within' => ['rule' => 'end_date_within', 'data' => ['days' => '2']],
                ]
            );
        }
    }

    /**
     * @param $title
     * @param $sendRules
     * @param $body
     * @param $limitEmails
     * @param $limitDays
     * @param $userUpgradeCriteria
     * @throws \XF\PrintableException
     */
    public function createEmail($title, $sendRules, $body, $limitEmails, $limitDays, $userUpgradeCriteria)
    {
        /** @var \ThemeHouse\Monetize\Entity\Email $email */
        $email = $this->app->em()->create('ThemeHouse\Monetize:Email');
        $email->title = $title;
        $email->send_rules = $sendRules;
        $email->body = $body;
        $email->format = 'html';
        $email->limit_emails = $limitEmails;
        $email->limit_days = $limitDays;
        $email->user_upgrade_criteria = $userUpgradeCriteria;
        $email->save(false);
    }

    /**
     * @param int $previousVersion
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    protected function createDefaultMessage($previousVersion = 0)
    {
        if ($previousVersion < 1000041) {
            $this->createMessage(
                'Your upgrade is about to expire',
                [
                    'day_type' => 'dom',
                    'dom' => [-1]
                ],
                "Hi {name},

This is a courtesy message to let you know that one of your user upgrades is about to expire in less than 7 days.",
                1,
                7,
                [
                    'is_active' => ['rule' => 'is_active'],
                    'end_date_within' => ['rule' => 'end_date_within', 'data' => ['days' => '7']],
                    'end_date_not_within' => ['rule' => 'end_date_not_within', 'data' => ['days' => '5']],
                ]
            );
        }
    }

    /**
     * @param $title
     * @param $sendRules
     * @param $body
     * @param $limitMessages
     * @param $limitDays
     * @param $userUpgradeCriteria
     * @throws \XF\PrintableException
     */
    public function createMessage($title, $sendRules, $body, $limitMessages, $limitDays, $userUpgradeCriteria)
    {
        /** @var \ThemeHouse\Monetize\Entity\Message $message */
        $message = $this->app->em()->create('ThemeHouse\Monetize:Message');
        $message->title = $title;
        $message->send_rules = $sendRules;
        $message->body = $body;
        $message->user_id = 1;
        $message->limit_messages = $limitMessages;
        $message->limit_days = $limitDays;
        $message->user_upgrade_criteria = $userUpgradeCriteria;
        $message->save(false);
    }

    /**
     *
     */
    public function upgrade1000011Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->createTable('xf_th_monetize_keyword', function (Create $table) {
            $table->addColumn('keyword_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('keyword', 'varchar(250)');
            $table->addColumn('replace_type', 'enum')
                ->values(['url', 'html']);
            $table->addColumn('replacement', 'blob');
            $table->addColumn('limit', 'int');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('user_criteria', 'mediumblob');
        });
    }

    /**
     *
     */
    public function upgrade1000012Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->createTable('xf_th_monetize_sponsor', function (Create $table) {
            $table->addColumn('th_sponsor_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('url', 'varchar(250)')->setDefault('');
            $table->addColumn('image', 'varchar(250)')->setDefault('');
            $table->addColumn('width', 'int')->setDefault(0);
            $table->addColumn('height', 'int')->setDefault(0);
        });
    }

    /**
     *
     */
    public function upgrade1000012Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->renameTable('xf_thmonetize_affiliate_link', 'xf_th_monetize_affiliate_link');
    }

    /**
     *
     */
    public function upgrade1000012Step3()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_node', function (Alter $table) {
            $table->addColumn('th_sponsor_id', 'int')->setDefault(0);
        });
    }

    /**
     *
     */
    public function upgrade1000013Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user', function (Alter $table) {
            $table->changeColumn('user_state')->addValues(['thmonetize_upgrade']);
        });
    }

    /**
     *
     */
    public function upgrade1000032Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->createTable('xf_th_monetize_upgrade_page', function (Create $table) {
            $table->addColumn('upgrade_page_id', 'int')->autoIncrement();
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('active', 'boolean')->setDefault(1);
        });
    }

    /**
     *
     */
    public function upgrade1000033Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->createTable('xf_th_monetize_upgrade_page_relation', function (Create $table) {
            $table->addColumn('upgrade_page_id', 'int');
            $table->addColumn('user_upgrade_id', 'int');
            $table->addColumn('display_order', 'int');
            $table->addColumn('featured', 'boolean')->setDefault(0);
            $table->addUniqueKey(['upgrade_page_id', 'user_upgrade_id']);
        });
    }

    // ############################# FINAL UPGRADE ACTIONS #############################

    /**
     *
     */
    public function upgrade1000033Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_upgrade_page', function (Alter $table) {
            $table->addColumn('display_order', 'int');
            $table->addColumn('user_criteria', 'mediumblob');
        });
    }

    // ############################# UNINSTALL #############################

    /**
     *
     */
    public function upgrade1000033Step3()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade', function (Alter $table) {
            $table->addColumn('thmonetize_features', 'mediumblob')->nullable();
        });
    }

    /**
     *
     */
    public function upgrade1000035Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_upgrade_page', function (Alter $table) {
            $table->addColumn('show_all', 'boolean')->setDefault(1);
            $table->addColumn('page_criteria', 'mediumblob');
            $table->dropColumns(['title']);
        });
    }

    /**
     *
     */
    public function upgrade1000035Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_sponsor', function (Alter $table) {
            $table->addColumn('directory', 'boolean')->setDefault(1);
        });
    }

    /**
     *
     */
    public function upgrade1000036Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_upgrade_page', function (Alter $table) {
            $table->addColumn('overlay', 'boolean')->setDefault(0);
            $table->addColumn('overlay_dismissible', 'boolean')->setDefault(0);
        });
    }

    /**
     *
     */
    public function upgrade1000036Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade', function (Alter $table) {
            $table->addColumn('thmonetize_style_properties', 'mediumblob')->nullable();
        });
    }

    /**
     *
     */
    public function upgrade1000037Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_upgrade_page', function (Alter $table) {
            $table->addColumn('page_criteria_overlay_only', 'boolean')->setDefault(1);
            $table->addColumn('accounts_page', 'boolean')->setDefault(0);
            $table->addColumn('error_message', 'boolean')->setDefault(0);
            $table->addColumn('upgrade_page_links', 'blob');
            $table->addColumn('accounts_page_link', 'boolean')->setDefault(1);
        });
    }

    // ############################# TABLE / DATA DEFINITIONS #############################

    /**
     *
     */
    public function upgrade1000038Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->createTable('xf_th_monetize_alert', function (Create $table) {
            $table->addColumn('alert_id', 'int')->autoIncrement();
            $table->addColumn('send_rules', 'blob');
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('user_id', 'int');
            $table->addColumn('link_url', 'varchar(250)');
            $table->addColumn('link_title', 'varchar(250)');
            $table->addColumn('body', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('next_send', 'int');
        });

        $schemaManager->createTable('xf_th_monetize_email', function (Create $table) {
            $table->addColumn('email_id', 'int')->autoIncrement();
            $table->addColumn('send_rules', 'blob');
            $table->addColumn('from_name', 'varchar(250)');
            $table->addColumn('from_email', 'varchar(250)');
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('format', 'enum')->values(['', 'html']);
            $table->addColumn('body', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('wrapped', 'boolean')->setDefault(1);
            $table->addColumn('unsub', 'boolean')->setDefault(1);
            $table->addColumn('receive_admin_email_only', 'boolean')->setDefault(1);
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('next_send', 'int');
        });

        $schemaManager->createTable('xf_th_monetize_message', function (Create $table) {
            $table->addColumn('message_id', 'int')->autoIncrement();
            $table->addColumn('send_rules', 'blob');
            $table->addColumn('title', 'varchar(250)');
            $table->addColumn('user_id', 'int');
            $table->addColumn('body', 'mediumblob');
            $table->addColumn('active', 'boolean')->setDefault(1);
            $table->addColumn('open_invite', 'boolean')->setDefault(1);
            $table->addColumn('conversation_locked', 'boolean')->setDefault(1);
            $table->addColumn('delete_type', 'enum')->values(['', 'deleted', 'deleted_ignored']);
            $table->addColumn('user_criteria', 'mediumblob');
            $table->addColumn('next_send', 'int');
        });
    }

    /**
     *
     */
    public function upgrade1000038Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_profile', function (Alter $table) {
            $table->addColumn('thmonetize_active_upgrades', 'mediumblob')->nullable();
        });
    }

    /**
     *
     */
    public function upgrade1000040Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->createTable('xf_th_monetize_alert_log', function (Create $table) {
            $table->addColumn('alert_log_id', 'int')->autoIncrement();
            $table->addColumn('log_date', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('alert_id', 'int');
            $table->addKey('log_date');
            $table->addKey('user_id');
            $table->addKey(['user_id', 'log_date']);
            $table->addKey(['user_id', 'alert_id']);
        });

        $schemaManager->createTable('xf_th_monetize_email_log', function (Create $table) {
            $table->addColumn('email_log_id', 'int')->autoIncrement();
            $table->addColumn('log_date', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('email_id', 'int');
            $table->addKey('log_date');
            $table->addKey('user_id');
            $table->addKey(['user_id', 'log_date']);
            $table->addKey(['user_id', 'email_id']);
        });

        $schemaManager->createTable('xf_th_monetize_message_log', function (Create $table) {
            $table->addColumn('message_log_id', 'int')->autoIncrement();
            $table->addColumn('log_date', 'int');
            $table->addColumn('user_id', 'int');
            $table->addColumn('message_id', 'int');
            $table->addKey('log_date');
            $table->addKey('user_id');
            $table->addKey(['user_id', 'log_date']);
            $table->addKey(['user_id', 'message_id']);
        });
    }

    /**
     *
     */
    public function upgrade1000040Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_alert', function (Alter $table) {
            $table->addColumn('limit_alerts', 'int')->setDefault(0);
            $table->addColumn('limit_days', 'int')->setDefault(0);
        });

        $schemaManager->alterTable('xf_th_monetize_email', function (Alter $table) {
            $table->addColumn('limit_emails', 'int')->setDefault(0);
            $table->addColumn('limit_days', 'int')->setDefault(0);
        });

        $schemaManager->alterTable('xf_th_monetize_message', function (Alter $table) {
            $table->addColumn('limit_messages', 'int')->setDefault(0);
            $table->addColumn('limit_days', 'int')->setDefault(0);
        });
    }

    /**
     *
     */
    public function upgrade1000041Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_alert', function (Alter $table) {
            $table->addColumn('user_upgrade_criteria', 'mediumblob');
        });

        $schemaManager->alterTable('xf_th_monetize_email', function (Alter $table) {
            $table->addColumn('user_upgrade_criteria', 'mediumblob');
        });

        $schemaManager->alterTable('xf_th_monetize_message', function (Alter $table) {
            $table->addColumn('user_upgrade_criteria', 'mediumblob');
        });
    }

    /**
     *
     */
    public function upgrade1000041Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_profile', function (Alter $table) {
            $table->addColumn('thmonetize_expired_upgrades', 'mediumblob')->nullable();
        });
    }

    /**
     *
     */
    public function upgrade1000131Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade', function (Alter $table) {
            $table->addColumn('thmonetize_custom_amount', 'boolean')->setDefault(0);
        });
    }

    /**
     * @param $previousVersion
     * @param array $stateChanges
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     * @throws \XF\PrintableException
     */
    public function upgrade1000231Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade', function (Alter $table) {
            $table->addColumn('thmonetize_allow_multiple', 'boolean')->setDefault(0);
        });
    }

    public function upgrade1000237Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade_active', function (Alter $table) {
            $table->addColumn('thmonetize_updated', 'int')->setDefault(0);
        });
    }

    public function upgrade1000237Step2()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade_expired', function (Alter $table) {
            $table->addColumn('thmonetize_updated', 'int')->setDefault(0);
        });
    }

    public function upgrade1000240Step1()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_th_monetize_keyword', function (Alter $table) {
            $table->addColumn('keyword_options', 'blob');
        });

        /** @var Keyword $keywordRepo */
        $keywordRepo = \XF::repository('ThemeHouse\Monetize:Keyword');
        $keywordRepo->rebuildKeywordCache();
    }

    // ############################# FINAL UPGRADE ACTIONS #############################

    public function postUpgrade($previousVersion, array &$stateChanges)
    {
        if ($this->applyDefaultPermissions($previousVersion)) {
            // since we're running this after data imports, we need to trigger a permission rebuild
            // if we changed anything
            $this->app->jobManager()->enqueueUnique(
                'permissionRebuild',
                'XF:PermissionRebuild',
                [],
                false
            );
        }

        $this->createDefaultUpgradePage($previousVersion);
        $this->createDefaultPaymentProvider($previousVersion);
        $this->createDefaultPaymentProfile($previousVersion);
        $this->createDefaultAlert($previousVersion);
        $this->createDefaultEmail($previousVersion);
        $this->createDefaultMessage($previousVersion);


        /** @var UserUpgrade $userUpgradeRepo */
        $userUpgradeRepo = \XF::repository('XF:UserUpgrade');
        $userUpgradeRepo->generateCssForThMonetizeUserUpgrades();
    }

    /**
     *
     */
    public function uninstallStep1()
    {
        $schemaManager = $this->schemaManager();

        foreach (array_keys($this->getTables()) as $tableName) {
            $schemaManager->dropTable($tableName);
        }
    }

    /**
     *
     */
    public function uninstallStep2()
    {
        $this->db()->update('xf_user', ['user_state' => 'disabled'], 'user_state = ?', 'thmonetize_upgrade');

        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user', function (Alter $table) {
            $table->changeColumn('user_state')->removeValues(['thmonetize_upgrade']);
        });
    }

    /**
     *
     */
    public function uninstallStep3()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_node', function (Alter $table) {
            $table->dropColumns(['th_sponsor_id']);
        });
    }

    /**
     *
     */
    public function uninstallStep4()
    {
        $schemaManager = $this->schemaManager();

        $schemaManager->alterTable('xf_user_upgrade', function (Alter $table) {
            $table->dropColumns(['thmonetize_features']);
        });
    }

    /**
     *
     * @throws \XF\PrintableException
     */
    public function uninstallStep5()
    {
        $phrases = $this->app->finder('XF:Phrase')->whereOr([
            ['title', 'LIKE', 'upgrade_page.%'],
        ])->fetch();

        foreach ($phrases as $phrase) {
            /** @var Phrase $phrase */
            $phrase->delete();
        }
    }

    /**
     *
     */
    public function uninstallStep6()
    {
        $this->db()->delete('xf_payment_provider', 'provider_id = ?', 'thmonetize_free');
        $this->db()->delete('xf_payment_profile', 'provider_id = ?', 'thmonetize_free');
    }
}
