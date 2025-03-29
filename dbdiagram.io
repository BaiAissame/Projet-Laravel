Table users {
  id int [primary key]
  name varchar
  email varchar [unique]
  email_verified_at timestamp
  password varchar
  remember_token varchar
  created_at timestamp
  updated_at timestamp
}

Table password_reset_tokens {
  email varchar [primary key]
  token varchar
  created_at timestamp
}

Table sessions {
  id varchar [primary key]
  user_id int
  ip_address varchar
  user_agent text
  payload longtext
  last_activity int
}

Table projects {
  id int [primary key]
  name varchar
  description text
  user_id int
  created_at timestamp
  updated_at timestamp
}

Table task_categories {
  id int [primary key]
  name varchar
  created_at timestamp
  updated_at timestamp
}

Table task_priorities {
  id int [primary key]
  name varchar
  created_at timestamp
  updated_at timestamp
}

Table tasks {
  id int [primary key]
  title varchar
  description text
  project_id int
  user_id int
  task_category_id int
  task_priority_id int
  created_at timestamp
  updated_at timestamp
}

Table task_user {
  id int [primary key]
  task_id int
  user_id int
  created_at timestamp
  updated_at timestamp
}

Ref: tasks.project_id > projects.id
Ref: tasks.task_category_id > task_categories.id
Ref: tasks.task_priority_id > task_priorities.id
Ref: task_user.task_id > tasks.id
Ref: task_user.user_id > users.id
Ref: projects.user_id > users.id
Ref: users.id > tasks.user_id
