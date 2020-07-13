import request from '../request'

/* 获取用户信息 */
export function getInfo() {
    return request('get', '/api/user');
}

/* 更新用户信息 */
export function update(body) {
    return request('post', '/api/user/update', body);
}

/* 更新用户信息 */
export function list(query) {
    return request('get', '/api/user/list', query);
}

/* 禁用|解禁用户 */
export function block(id) {
    return request('post', '/api/user/block/'+id);
}

export function passwdReset(body) {
    return request('post', '/api/user/password/reset', body);
}

export function addUser(body) {
    return request('post', '/api/user/create', body);
}