# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-12-13

### Added
- Initial release of PHPMaxBot
- Support for MAX Messenger Bot API
- Webhook and Long Polling modes
- Command handlers with parameter support
- Event handlers for all MAX update types
- Callback (action) handlers with regex support
- Keyboard helper class for creating inline keyboards
- Button types: callback, link, request_contact, request_geo_location, chat
- Complete Bot API wrapper with all methods:
  - Messages: send, get, edit, delete
  - Chats: get, edit, members management
  - Bot: get/edit info, commands management
  - Pinned messages: get, pin, unpin
  - Actions and callbacks
- Exception handling with ApiException and MaxBotException
- Debug mode for development
- PSR-4 autoloading
- Comprehensive documentation
- Example bots (simple-bot.php, keyboard-bot.php)

### Features
- Auto-detection of CLI vs Webhook mode
- Smart message routing (auto-detect chat_id/user_id from update)
- Support for multiple event types
- Regex pattern matching for commands and callbacks
- Intent support for buttons (positive, negative, default)
- Full support for attachments and keyboards
- Error handling with detailed context

## [Unreleased]

### Planned
- File upload support
- Image, video, audio attachment helpers
- Middleware support
- State management
- Rate limiting
- Logging integration (PSR-3)
- Unit tests
- CI/CD integration
