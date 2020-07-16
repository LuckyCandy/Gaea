<template>
    <div>
        <div class="lc-header">
            <div class="layout-logo"></div>
            <div class="user-control">
                <Avatar :style="{width: '80px', height: '40px', borderRadius: 0}" src="/images/login.png"/>
            </div>
        </div>

        <div class="lc-content">
            <div class="admin-content">
                <div class="admin-menu">
                    <Menu active-name="1">
                        <MenuItem name="0" :to="{name: 'dashboard'}">
                            <Icon type="ios-arrow-back" />
                            返回首页
                        </MenuItem>
                        <MenuItem name="1" :to="{name: 'admin-user-list'}">
                            <Icon type="ios-people" />
                            所有用户
                        </MenuItem>
                        <MenuItem name="2" :to="{name: 'admin-log-list'}">
                            <Icon type="ios-paper" />
                            日志列表
                        </MenuItem>
                    </Menu>

                </div>
                <div class="admin-item">
                    <router-view></router-view>
                </div>
            </div>
        </div>

    </div>
</template>

<script>


    export default {
        name: 'AdminLayout',
        data() {
            return {
            }
        },
        created() {

        },
        methods: {
        }
    }
</script>

<style scoped>
    .layout-logo{
        width: 150px;
        height: 40px;
        background: url(/images/nav-logo.png) left center no-repeat;
        position: relative;
        display: inline-block;
        padding: 8px 10px;
        top: 15px;
        left: 10px;
    }
    .user-control{
        position: relative;
        top: 15px;
        float: right;
        right: 8px;
    }
    .lc-header {
        width: 100%;
        height: 64px;
        background: black;
        border-bottom: 1px solid;
        position: absolute;
    }
    .lc-content {
        display: flex;
        position: absolute;
        top: 64px;
        bottom: 0;
        overflow: hidden;
        background: rgb(242, 242, 242);
        width: 100%;
    }
    .admin-content {
        position: relative;
        width: 100%;
    }
    .admin-menu {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 240px;
        background: white;
    }
    .admin-item {
        position: absolute;
        right: 0;
        height: 100%;
        left: 240px;
    }
</style>