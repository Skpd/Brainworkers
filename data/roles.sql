INSERT INTO user_role_linker (user_id, role_id) (
  SELECT id, 'user' FROM users WHERE id > 3
)