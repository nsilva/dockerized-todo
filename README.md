# To-Do Application

## Summary
This is more than just the typical to-do app. This app demonstrates the use of several concepts
- Docker and Docker Compose
- Laravel/PHP Backend
  - Queues(Redis)
  - Notifications
  - Observers
  - Policies
  - Sanctum Authentication
  - Scheduler
  - Relationships
  - Enums
  - Commands
    
- Vue Frontend
  - Components Reusability
  - Routing
  - Testing
  - Emitters
 
## Dependencies
- Node
- Docker
- Docker Compose

## How to Run this project
To run the project execute `./up.sh` from the project's root folder, this will start the Docker container. The UI will be available at `localhost:8001`, additionally, PHPMyAdmin can be accessed at `localhost:8003` and the email inbox, where the system emails will land will be located at `localhost:8004`.

For convenience, the application includes a seeder that creates a user with some to-dos. You can access with the following credentials:
Email: test@example.com
Password: password123

## Infrastructure(Docker)
The project infrastructure is built upon Docker with Docker Compose. At the root folder you can find the `docker-compose.yml` file. The defined containers are as follows:
- front-end: This container will hold the Vue frontend. The volumes will map the `frontend` folder.
- backend-laravel: This container holds the Laravel application. The volumes will map the `backend` folder.
- redis: This will hold the redis instance to be used by the `queue` container.
- queue-worker: This container will be in charge of running the queue worker.
- scheduler: This container will run the Laravel scheduler necessary for the tasks in progress for more than 24 hours by running `reminder:send` command
- db: This container run the project's database
- phpmyadmin: This container facilitates visual access to the database on `localhost:8003` with PHPMyAdmin.
- mailpit: This container holds the Mailpit inbox where the system email will land.

## Backend (Laravel)
The Laravel backend leverages several features from the framework
- Queues: For the tasks that are in progress for more than 24 hours, it is necessary to notify the users about the pending tasks. Since email notifications can be a slow process, the emailing is processed in a queue using Redis.
- Notifications: The pending tasks email is send using the notifications system, where each use with pending tasks is notified via email
- Observers: It is assumed that updating a task can have side effects in two cases
  - If a parent task is not in progress and a child task is set to in progress, the parent task should be set to in progress as well
  - If a parent task is set as complete, all the subtasks should be set as complete
 To cover theses cases, a model observer was used to observe the `updated` event
- Policies: A policy has been used to determine if a user can view or edit a task
- Sanctum: The authentication is done using Sanctum to issue access token and as middleware to validate the token in each request
- Scheduler/Command: A command is in charge of fetching the users with pending taks for more than 24 hours, along with the number of pendings tasks. The command is scheduled to execute every 5 minutes for testing purposes.
- Relationships: Give that tasks and subtasks have the same properties, the `Todo` model was set to have a relationship with itself, so that if the `parent_id` is null, it is a parent task, otherwise it is a subtask.
- Enums: To handle the different possible statuses for a task, the newly introduced PHP `enum` structure has been used to have more flexibility if a new status is introduced.

Additionally, basic testing coverage was created to test the API request responses.

## Frontend (Vue)
The frontend application handles the user login, account creation and to-dos creation/update. The implementation uses some basics concepts of the framework:
- Component reusability: It is clearly seen in the To-Dos screen, where the `Todo` component renders copies of itself if the task has subtasks.
- Routing: To render the different screens, the Vue router is used for redirection
- Testing: Basic tests were created using Vitest. This is just a demonstration on testing and far from being a proper test coverage.
- Emitters: Severals emitters are used across the application to handle the different event caused by adding/updating tasks.

## Other considerations
For the styling, I used Tailwind with nested styles. I also used Vite to create the Vue project

## Relevant URLs

- UI: localhost:8001
- API: localhost:8002
- DBAdmin: localhost:8003
- Mail Inbox: localhost:8004

## Possible Improvements
There are several opportunities to improve this application, but for the pusposes it was created, I believe it covers pretty much everything. At the time of writing, there quite a few improvement I can think of:
- Improve testing coverage
- Filter tasks by status
- Remove tasks marked as completed from the main list
- Add an email confirmation message upon registration



