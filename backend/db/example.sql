Table users {
  id UUID [primary key]
  username VARCHAR(50) [unique, not null]
  email VARCHAR(100) [unique, not null]
  password VARCHAR(255) [not null] 
  profile_picture TEXT
  bio TEXT
  created_at TIMESTAMP 
  updated_at TIMESTAMP
}

Table portfolios {
  id UUID [primary key]
  user_id UUID [not null, unique, ref: > users.id] 
  title VARCHAR(255)
  description TEXT
  theme VARCHAR(50) 
  created_at TIMESTAMP 
  updated_at TIMESTAMP
}

Table portfolio_components {
  id UUID [primary key]
  portfolio_id UUID [not null, ref: > portfolios.id]
  type ENUM('hero', 'spotify_embed', 'item_card', 'gallery', 'video', 'social_links')
  content TEXT
  position INT 
  created_at TIMESTAMP 
  updated_at TIMESTAMP
}

Table posts {
  id UUID [primary key]
  user_id UUID [not null, ref: > users.id]
  type ENUM('blog', 'thread', 'question', 'update')
  title VARCHAR(255) 
  content TEXT [not null]
  media_url TEXT 
  created_at TIMESTAMP 
  updated_at TIMESTAMP
}

Table comments {
  id UUID [primary key]
  post_id UUID [not null, ref: > posts.id]
  user_id UUID [not null, ref: > users.id]
  content TEXT [not null]
  created_at TIMESTAMP 
}

Table tags {
  id UUID [primary key]
  name VARCHAR(50) [unique, not null]
}

Table post_tags {
  post_id UUID [not null, ref: > posts.id]
  tag_id UUID [not null, ref: > tags.id]
  indexes {
    (post_id, tag_id) [unique]
  }
}

Table likes {
  id UUID [primary key]
  user_id UUID [not null, ref: > users.id]
  post_id UUID [not null, ref: > posts.id]
  created_at TIMESTAMP 
}

Table authentication_tokens {
  id UUID [primary key]
  user_id UUID [not null, ref: > users.id]
  token VARCHAR(255) [not null, unique]
  expires_at TIMESTAMP [not null]
}
