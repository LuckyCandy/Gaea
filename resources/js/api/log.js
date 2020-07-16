import request from '../request'

/* 获取日志列表 */
export function list(query) {
    return request('get', '/api/log/list', query);
}