<?php
use Kreait\Firebase\Database\RuleSet;

	try {

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS admins (
								id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
								username VARCHAR(50) NOT NULL DEFAULT '',
                                salt CHAR(3) NOT NULL DEFAULT '',
                                password VARCHAR(32) NOT NULL DEFAULT '',
                                fullname VARCHAR(150) NOT NULL DEFAULT '',
                                createAt int(11) UNSIGNED DEFAULT 0,
								u_agent varchar(300) DEFAULT '',
								ip_addr CHAR(32) NOT NULL DEFAULT '',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS feeds (
								id varchar(255) CHARACTER SET utf8 NOT NULL,
								text text CHARACTER SET utf8 NOT NULL,
								userId text CHARACTER SET utf8 NOT NULL,
                                createAt bigint(16) UNSIGNED DEFAULT 0,
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS feeds_vip (
								id varchar(255) CHARACTER SET utf8 NOT NULL,
								text text CHARACTER SET utf8 NOT NULL,
								userId text CHARACTER SET utf8 NOT NULL,
                                createAt bigint(16) UNSIGNED DEFAULT 0,
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS userfb (
								id varchar(255) CHARACTER SET utf8 NOT NULL,
								text text CHARACTER SET utf8 NOT NULL,
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

		$sth = $dbo->prepare("CREATE TABLE IF NOT EXISTS gcm_history (
								id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
								msgTitle varchar(300) NOT NULL DEFAULT '',
								msg varchar(300) NOT NULL DEFAULT '',
								toId varchar(255) CHARACTER SET utf8 NOT NULL,
								status int(10) UNSIGNED NOT NULL DEFAULT '0',
								success int(10) UNSIGNED NOT NULL DEFAULT '0',
								createAt int(11) UNSIGNED DEFAULT '0',
								PRIMARY KEY  (id)) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		$sth->execute();

	} catch (Exception $e) {

		die ($e->getMessage());
	}


$ruleSet = RuleSet::fromArray(
  [
    "rules" => [
        ".read" => false,
        ".write" => false,
        "UpdateAppVersion" => [
            ".read" => true,
            ".write" => false
        ],
        "ContactUs" => [
            ".write" => "auth !== null",
            "\$uid" => [
                ".read" => "auth.uid == \$uid"
            ]
        ],
        "Reports" => [
            ".write" => "auth !== null",
            "\$uid" => [
                ".read" => "auth.uid == \$uid"
            ]
        ],
        "ReportsAPP" => [
            ".write" => "auth !== null",
            "\$uid" => [
                ".read" => "auth.uid == \$uid"
            ]
        ],
        "newComments" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "BlackList" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "API_CALL" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],

        "should_disconnect" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "ice_servers" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "ice_candidates" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "answers" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "offers" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "online_devices" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],

        "chats" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            "\$cid" => [
                ".indexOn" => [
                    ".key",
                    "date",
                    "delivered"
                ],
                "\$pid" => [
                    ".indexOn" => [
                        ".key",
                        "date",
                        "delivered"
                    ]
                ]
            ],
            ".indexOn" => [
                ".key",
                "date",
                "delivered"
            ]
        ],
        "ChatsUsers" => [
            "\$uid" => [
                ".read" => "auth.uid == \$uid",
                ".write" => "auth.uid == \$uid",
                "\$cid" => [
                    ".read" => "auth.uid == \$uid || \$cid == auth.uid+'-'+\$uid || \$cid == \$uid+'-'+auth.uid || root.child('groups').child(\$cid).child('userIdss').child(auth.uid).exists()",
                    ".write" => "auth.uid == \$uid || \$cid == auth.uid+'-'+\$uid || \$cid == \$uid+'-'+auth.uid  || root.child('groups').child(\$cid).child('userIdss').child(auth.uid).exists()",
                    ".validate" => "newData.hasChildren(['chatName', 'read', 'lastMessage'])"
                ]
            ]
        ],
        "Comments" => [
            ".indexOn" => [
                ".key",
                "id"
            ],
            ".read" => "auth !== null",
            "\$feedId" => [
                ".indexOn" => [
                    ".key",
                    "id"
                ],
                "\$cid" => [
                    ".indexOn" => [
                        ".key",
                        "id"
                    ]
                ],
                ".write" => "root.child('Feeds').child(\$feedId).exists()  || root.child('FeedsVip').child(\$feedId).exists() || root.child('Questions').child(\$feedId).exists()"
            ]
        ],
        "Crashes" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "Deleted" => [
            ".read" => false,
            ".write" => "auth !== null"
        ],
        "Followers" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "Followings" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "groups" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "inbox" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "FeedsVip" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => [
                "timestamp",
                "imported",
                "userId"
            ],
            "\$id" => [
                ".indexOn" => [
                    "timestamp",
                    "imported",
                    "userId"
                ]
            ]
        ],
        "Questions" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => [
                "timestamp",
                "imported",
                "filterId",
                "userId"
            ],
            "\$id" => [
                ".indexOn" => [
                    "timestamp",
                    "imported",
                    "filterId",
                    "userId"
                ],
                ".validate" => "newData.hasChildren(['text', 'commentsCount', 'imported', 'likesCount', 'userId'])"
            ]
        ],
        "new_user" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "newFeed" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "Notifications" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => "timestamp",
            "\$uid" => [
                "\$notificationId" => [
                    ".read" => "auth !== null",
                    ".write" => "auth !== null",
                    ".validate" => "newData.hasChildren(['fromUserId', 'id', 'itemId', 'itemType', 'notificationType', 'toUserId'])"
                ]
            ]
        ],

        "Invitations" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => "timestamp",
            "\$uid" => [
                ".read" => "auth !== null",
                ".write" => "auth !== null",
            ]
        ],

        "Friends" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => ".value",
            "\$uid" => [
                ".read" => "auth !== null",
                ".write" => "auth == \$uid",
                ".indexOn" => ".value",
                "\$user_id" => [
                    ".write" => "root.child('Friends').child(\$user_id).child(\$uid).exists()"
                ]
            ]
        ],
        "FriendshipState" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            "\$combineIds" => [
                ".read" => "auth !== null",
                ".write" => "auth !== null",
            ]
        ],


        "SETTINGS" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            "\$uid" => [
                ".read" => "auth !== null",
                ".write" => "auth == \$uid",
            ]
        ],

        "NotificationsPath" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "NotificationsState" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "user_fcm_ids" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "UsersMy" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null"
        ],
        "users" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => [
                "online",
                ".key",
                "id",
                "userName",
                "imported",
                "deleted",
                "timestamp"
            ],
            "\$uid" => [
                ".indexOn" => [
                    "online",
                    ".key",
                    "id",
                    "userName",
                    "imported",
                    "deleted",
                    "timestamp"
                ]
            ]
        ],
        "Feeds" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => [
                "timestamp",
                "imported",
                "userId"
            ],
            "\$id" => [
                ".indexOn" => [
                    "timestamp",
                    "imported",
                    "userId"
                ],
                ".validate" => "newData.hasChildren(['text', 'commentsCount', 'imported', 'likesCount', 'userId'])"
            ]
        ],
        "myFeeds" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => ".key",
            "\$uid" => [
                ".indexOn" => ".key"
            ]
        ],
        "userNames" => [
            ".read" => "auth !== null",
            ".write" => "auth !== null",
            ".indexOn" => ".key"
        ],
        "FeedsReactions" => [
            ".read" => "auth !== null",
            "\$feedId" => [
                ".write" => "root.child('Feeds').child(\$feedId).exists() || root.child('FeedsVip').child(\$feedId).exists() || root.child('Questions').child(\$feedId).exists()"
            ]
        ]
    ]
]
);
