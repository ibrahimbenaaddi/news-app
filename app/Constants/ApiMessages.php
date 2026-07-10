<?php

namespace App\Constants;

enum ApiMessages: string
{
    case CREATE = 'create';
    case READ = 'read';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case LOGIN = 'login';
    case REGISTER = 'register';
    case LOGOUT = 'logout';
    case AUTH = 'auth';
    case AUTHORIZATION = 'authorization';

    public function success(): string
    {
        return match ($this) {
            self::CREATE => 'Resource created successfully.',
            self::READ => 'Resource retrieved successfully.',
            self::UPDATE => 'Resource updated successfully.',
            self::DELETE => 'Resource deleted successfully.',
            self::LOGIN => 'You have logged in successfully.',
            self::REGISTER => 'Your account has been created successfully.',
            self::LOGOUT => 'You have been logged out successfully.',
        };
    }

    public function error(): string
    {
        return match ($this) {
            self::CREATE => 'Failed to create resource.',
            self::READ => 'Failed to retrieve resource.',
            self::UPDATE => 'Failed to update resource.',
            self::DELETE => 'Failed to delete resource.',
            self::LOGIN => 'The email or password you entered is incorrect.',
            self::REGISTER => 'Registration could not be completed. Please try again.',
            self::LOGOUT => 'Invalid or expired session token.',
            self::AUTH => 'Unauthenticated.',
            self::AUTHORIZATION => 'You are not allowed to perform this.',
        };
    }
}