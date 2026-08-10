# Advanced Kanban Demo Project

> A **live demo** showcasing the powerful features of [Advanced Kanban](https://laravelplugins.com/filament/advanced-kanban/) - a premium Kanban board plugin for Filament 4.x

## 🎯 About This Demo

This is a fully functional demo application that demonstrates the capabilities of **Advanced Kanban**, a premium Kanban board plugin for Filament. You can explore all features in real-time to see how it works before purchasing.

- **📖 Documentation**: [https://laravelplugins.com/filament/advanced-kanban/](https://laravelplugins.com/filament/advanced-kanban/)
- **🎥 Demo Video**: [Watch on YouTube](https://www.youtube.com/watch?v=lqVxcOwHQAA)
- **💰 Purchase**: [Buy Advanced Kanban](https://asmit-nepali.privato.pub/portal/filament-advanced-kanban/checkout)
- **🔌 Filament Plugin Page**: [View on Filament](https://filamentphp.com/plugins/asmit-nepali-advanced-kanban)
- **🌐 All Plugins**: [Laravel Plugins](https://laravelplugins.com/)

## ✨ Features Showcased

This demo highlights the following advanced features:

### 🎨 Core Features

- **Drag & Drop Interface**: Intuitive drag-and-drop functionality for moving records between columns
- **Workflow Transitions**: Define allowed status transitions to control record movement  
- **Real-time Search**: Search across multiple fields with debounced input
- **Advanced Filtering**: Custom filter forms with multiple field types
- **Pagination**: Load more records per column with infinite scroll
- **Query Modifiers**: Fine-tune search queries with advanced filtering options

### 🚀 Advanced Capabilities

- **Custom Card Components**: Rich, customizable card displays with badges, avatars, and metadata
- **Column Header Actions**: Add new records directly from column headers
- **Record Actions**: Edit, delete, and view actions on individual cards
- **Card Locking**: Prevent specific cards from being moved based on conditions
- **Session Persistence**: Filters and search are automatically saved in session
- **Tab Filtering**: Filter records by status using tab navigation
- **MRR (Manage Related Records)**: Full Kanban support for related records in Filament resources

## 📋 What's Included

This demo project includes:

### Kanban Pages

1. **Tasks Kanban** (`/admin/kanban-task`)
   - Standalone Kanban page with all features
   - Tab filtering (All, Pending, In Progress)
   - Custom card components with priority badges
   - Advanced filtering by project, status, and priority

2. **Project Tasks Kanban** (`/admin/projects/{id}/tasks-kanban`)
   - MRR Kanban for managing tasks within a project
   - Same powerful features as standalone Kanban
   - Automatically filters by parent project

### List Views

1. **Project Tasks List** (`/admin/projects/{id}/tasks`)
   - Traditional Filament table view
   - Seamless switching between table and Kanban views
   - Complete CRUD operations

### Demo Data

- Sample projects with various tasks
- Different task statuses and priorities
- Assigned users and due dates
- Realistic data to showcase all features

## 🎓 Explore the Features

### Workflow Transitions

See how status transitions are controlled - some moves are allowed while others are restricted, ensuring data integrity.

### Card Locking

Unassigned tasks are locked in certain columns, preventing accidental moves until properly assigned.

### Custom Components

- **Card Component**: Displays task title, description, priority badge, assignee avatar, and due date
- **Column Header**: Shows status icon, label, description, and record count badge

### Advanced Filtering

- Filter by project, status, and priority
- Multiple selection support
- Default filter values for quick views
- Session persistence for better UX

### Search & Pagination

- Real-time search across title and description
- Infinite scroll pagination
- Load more records per column on demand

## 🛠️ Technology Stack

- **Framework**: Laravel 12.x
- **Admin Panel**: Filament 4.x
- **PHP**: 8.4+
- **Frontend**: Tailwind CSS 4.x, Alpine.js
- **Database**: SQLite (for demo purposes)

## 🎯 Use Cases

This demo shows how Advanced Kanban can be used for:

- **Task Management**: Project and task tracking
- **Workflow Management**: Status-based workflows with transitions
- **Team Collaboration**: Assign tasks and track progress
- **Priority Management**: Visual priority indicators
- **Related Records**: Managing child records within parent resources

## 📄 License

This demo project is open source and available for educational purposes. The Advanced Kanban plugin itself is a premium plugin that requires a license for production use.

---

**Ready to build something amazing?** 

- **💰 [Purchase Advanced Kanban](https://asmit-nepali.privato.pub/portal/filament-advanced-kanban/checkout)** - Start building powerful Kanban boards today!
- **📖 [Read the Documentation](https://laravelplugins.com/filament/advanced-kanban/)** - Comprehensive guides and API reference
- **🎥 [Watch the Demo Video](https://www.youtube.com/watch?v=lqVxcOwHQAA)** - See it in action

Made with ❤️ by [Asmit Nepali](https://github.com/asmitnepali)

---

**Browse more plugins**: [Laravel Plugins](https://laravelplugins.com/) - A collection of powerful and flexible plugins for Filament
