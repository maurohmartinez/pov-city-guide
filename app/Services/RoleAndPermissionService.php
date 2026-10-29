<?php

namespace App\Services;

class RoleAndPermissionService
{
    public const string ROLE_SUPERADMIN = 'superadmin';

    public const string ROLE_ADMIN = 'admin';

    public const string ROLE_EDITOR = 'editor';

    public const string ROLE_VIEWER = 'viewer';

    public const array ROLES = [
        self::ROLE_SUPERADMIN,
        self::ROLE_ADMIN,
        self::ROLE_EDITOR,
        self::ROLE_VIEWER,
    ];

    public const array PERMISSIONS = [
        ...self::PERMISSIONS_ARTICLE,
        ...self::PERMISSIONS_CATEGORY,
        ...self::PERMISSIONS_TAG,
        ...self::PERMISSIONS_AUTHOR,
        ...self::PERMISSIONS_CUSTOMER,
        ...self::PERMISSIONS_ADMIN,
        ...self::PERMISSIONS_SETTING,
        ...self::PERMISSIONS_VIDEO,
    ];

    // Article
    public const string PERMISSION_ARTICLE_READ = 'article.read';

    public const string PERMISSION_ARTICLE_CREATE = 'article.create';

    public const string PERMISSION_ARTICLE_UPDATE = 'article.update';

    public const string PERMISSION_ARTICLE_DELETE = 'article.delete';

    public const array PERMISSIONS_ARTICLE = [
        self::PERMISSION_ARTICLE_READ,
        self::PERMISSION_ARTICLE_CREATE,
        self::PERMISSION_ARTICLE_UPDATE,
        self::PERMISSION_ARTICLE_DELETE,
    ];

    // Categories
    public const string PERMISSION_CATEGORY_READ = 'caregory.read';

    public const string PERMISSION_CATEGORY_CREATE = 'caregory.create';

    public const string PERMISSION_CATEGORY_UPDATE = 'caregory.update';

    public const string PERMISSION_CATEGORY_DELETE = 'caregory.delete';

    public const array PERMISSIONS_CATEGORY = [
        self::PERMISSION_CATEGORY_READ,
        self::PERMISSION_CATEGORY_CREATE,
        self::PERMISSION_CATEGORY_UPDATE,
        self::PERMISSION_CATEGORY_DELETE,
    ];

    // Tags
    public const string PERMISSION_TAG_READ = 'tag.read';

    public const string PERMISSION_TAG_CREATE = 'tag.create';

    public const string PERMISSION_TAG_UPDATE = 'tag.update';

    public const string PERMISSION_TAG_DELETE = 'tag.delete';

    public const array PERMISSIONS_TAG = [
        self::PERMISSION_TAG_READ,
        self::PERMISSION_TAG_CREATE,
        self::PERMISSION_TAG_UPDATE,
        self::PERMISSION_TAG_DELETE,
    ];

    // Authors
    public const string PERMISSION_AUTHOR_READ = 'author.read';

    public const string PERMISSION_AUTHOR_CREATE = 'author.create';

    public const string PERMISSION_AUTHOR_UPDATE = 'author.update';

    public const string PERMISSION_AUTHOR_DELETE = 'author.delete';

    public const array PERMISSIONS_AUTHOR = [
        self::PERMISSION_AUTHOR_READ,
        self::PERMISSION_AUTHOR_CREATE,
        self::PERMISSION_AUTHOR_UPDATE,
        self::PERMISSION_AUTHOR_DELETE,
    ];

    // Customers
    public const string PERMISSION_CUSTOMER_READ = 'customer.read';

    public const string PERMISSION_CUSTOMER_UPDATE = 'customer.update';

    public const string PERMISSION_CUSTOMER_DELETE = 'customer.delete';

    public const array PERMISSIONS_CUSTOMER = [
        self::PERMISSION_CUSTOMER_READ,
        self::PERMISSION_CUSTOMER_UPDATE,
        self::PERMISSION_CUSTOMER_DELETE,
    ];

    // Admins
    public const string PERMISSION_ADMIN_READ = 'admin.read';

    public const string PERMISSION_ADMIN_CREATE = 'admin.create';

    public const string PERMISSION_ADMIN_UPDATE = 'admin.update';

    public const string PERMISSION_ADMIN_DELETE = 'admin.delete';

    public const array PERMISSIONS_ADMIN = [
        self::PERMISSION_ADMIN_READ,
        self::PERMISSION_ADMIN_CREATE,
        self::PERMISSION_ADMIN_UPDATE,
        self::PERMISSION_ADMIN_DELETE,
    ];

    // Admins
    public const string PERMISSION_SETTING_READ = 'setting.read';

    public const string PERMISSION_SETTING_UPDATE = 'setting.update';

    public const array PERMISSIONS_SETTING = [
        self::PERMISSION_SETTING_READ,
        self::PERMISSION_SETTING_UPDATE,
    ];

    // Video
    public const string PERMISSION_VIDEO_READ = 'video.read';

    public const string PERMISSION_VIDEO_CREATE = 'video.create';

    public const string PERMISSION_VIDEO_UPDATE = 'video.update';

    public const string PERMISSION_VIDEO_DELETE = 'video.delete';

    public const array PERMISSIONS_VIDEO = [
        self::PERMISSION_VIDEO_READ,
        self::PERMISSION_VIDEO_CREATE,
        self::PERMISSION_VIDEO_UPDATE,
        self::PERMISSION_VIDEO_DELETE,
    ];
}
