drop table if exists users;
create table if not exists users (
	id int not null auto_increment,
	username varchar(60) not null default '',
	password varchar(64) not null default '',
	email varchar(64) not null default '',
	nickname varchar(255) not null default '',
	photo varchar(255) not null default '',
	role varchar(30) not null default 'member',
	state varchar(20) not null default 'pending',
	primary key (id),
	unique key admin_uniq (username, email)
) engine=innodb charset=utf8;

truncate table users;

insert into users (username, password, email, nickname, photo, role, state) values ('admin', '$P$BjvrSYhjj87yrv1IecYnr8Qv9ysjXf.', 'admin@localhost.com','Mr Nhật', 'avatars/admin_photo.jpg', 'administrator', 'checked');

drop table if exists options;
create table if not exists options (
	option_name varchar(60) not null default '',
	option_value text not null,
	option_desc varchar(500) not null default '',
	autoload enum('y','n') not null default 'n',
	primary key (option_name)
) engine=innodb charset=utf8;

truncate table options;

insert into options (option_name, option_desc, option_value, autoload) values 
('site_url','Địa chỉ ứng dụng','http://localhost/dashboard/','y'),
('row_per_page','Số dòng hiển thị trên một trang','15','y'),
('roles','Quyền hạn người dùng','a:0:{}','y'),
('login_code','Bật mã bảo vệ khi đăng nhập','n','y');

drop table if exists roles;
create table if not exists roles (
	id int not null auto_increment,
	role_alias varchar(64) not null default '',
	role_name varchar(500) not null default '',
	role_desc varchar(500) not null default '',
	primary key (id),
	unique key role_uniq (role_alias)
) engine=innodb charset=utf8;

truncate table roles;

insert into roles (role_alias, role_name, role_desc) values
('administrator', 'Quản trị hệ thống', 'Quản trị hệ thống'),
('admin', 'Quản trị viên', 'Quản trị viên'),
('account','Kế toán','Kế toán'),
('member','Thành viên','Thành viên');

drop table if exists events;
create table if not exists events (
	id int not null auto_increment,
	event_alias varchar(64) not null default '',
	event_name varchar(500) not null default '',
	event_desc varchar(500) not null default '',
	primary key (id),
	unique key alias (event_alias)
) engine=innodb charset=utf8;

truncate table events;

insert into events (event_alias, event_name, event_desc) values
('admin','Quản trị viên', 'Quản lý danh sách quản trị viên trên hệ thông'),
('role','Phân quyền người dùng','Phân quyền sử dụng các điều kiện trên hệ thông'),
('profile','Cập nhật thông tin cá nhân','Cập nhật thông tin cá nhân'),
('logout','Thoát ứng dụng','Thoát khỏi ứng dụng'),
('login','Đăng nhập hệ thông','Đăng nhập hệ thống'),
('grant', 'Cài đặt quyền truy cập vào sự kiện','Cài đặt quyền truy cập vào sự kiện'),
('forgot','Quên mật khẩu','Lấy lại thông tin đăng nhập nều như đã quên'),
('action','Quản lý các sự kiện trang','Quản lý các sự kiện trang');
