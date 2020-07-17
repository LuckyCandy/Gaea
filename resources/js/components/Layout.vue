<template>
    <div>
        <div class="lc-header">
            <div class="layout-logo"></div>
            <div class="user-control">
                <div v-if="isLogin" @click="logout">
                    <img style="height: 30px;width: 30px" src="/images/log-out.png" />
                </div>
                <div v-else @click="isShowLogin = true">
                    <img style="height: 30px;width: 30px" src="/images/log-in.png" />
                </div>
            </div>
        </div>

        <div class="lc-content">
            <router-view v-if="!isReload"></router-view>
        </div>

        <!-- 登录弹框 -->
        <Modal v-model="isShowLogin" :closable="false" :mask-closable="false" width="350px" :styles="{top: '152px'}">
            <!--<div class="circle"></div>-->
            <div slot="header">
                <img src="/images/login-title.png" />
            </div>
            <Form ref="loginForm" :model="formData" :rules="ruleValidate">
                <FormItem prop="email" :show-message="false" :style="{marginBottom: '5px'}">
                    <Label :style="{fontSize: '13px', lineHeight: '5px'}"><span style="color:red">*</span> 邮箱</Label>
                    <Input prefix="ios-mail" v-model="formData.email"></Input>
                </FormItem>
                <FormItem prop="password" :show-message="false" :style="{marginBottom: '30px'}">
                    <Label :style="{fontSize: '13px', lineHeight: '5px'}"><span style="color:red">*</span> 密码</Label>
                    <Input prefix="ios-lock" v-model="formData.password" type="password" password ></Input>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button type="primary" size="large" long @click="handleLogin" :loading="isDoLogin">
                    <span v-if="!isDoLogin">登 录!</span>
                    <span v-else>正在登录...</span>
                </Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import {login} from "../api/login";
    import {getInfo} from "../api/user";
    import adminRoutes from '../router/admin';
    import defaultRoutes from '../router/routes';
    import errorRoutes from '../router/error';

    export default {
        name: 'App',
        data() {
            return {
                isShowLogin: false,
                isDoLogin: false,
                isReload: false,
                isLogin: false,
                formData: {
                    email: '',
                    password: ''
                },
                ruleValidate: {
                    password: [
                        { required: true, message: '', trigger: 'blur' }
                    ],
                    email: [
                        { required: true, message: '', trigger: 'blur' },
                    ]
                }
            }
        },
        created() {
            /* 获取登录人信息 */
            getInfo().then(response => {
                this.isLogin = true;
                /* 登录成功，通知组件刷新 */
                EBUS.$emit('EVENT-USER-LOGIN', response.data);

                if (this.$router.options.routes.length === 1) {
                    if (Number(response.data.is_admin) === 1 ) {
                        defaultRoutes.push.apply(defaultRoutes, [adminRoutes, errorRoutes]);
                        this.$router.addRoutes(adminRoutes);
                    } else {
                        defaultRoutes.push.apply(defaultRoutes, errorRoutes);
                    }

                    this.$router.options.routes = defaultRoutes;
                    this.$router.addRoutes(errorRoutes);
                }
            }).catch(() => {
                window.localStorage.removeItem('gaea.user');
                this.$router.options.routes = defaultRoutes;
                this.isLogin = false;
                setTimeout(() => {
                    this.isShowLogin = true;
                }, 500);
            });
        },
        methods: {
            handleLogin() {
                this.$refs.loginForm.validate((valid) => {
                    if (valid) {
                        this.isDoLogin = true;
                        login(this.formData).then(response => {
                            window.localStorage.setItem('gaea.user', JSON.stringify(response.data));
                            this.isLogin = true;
                            this.isDoLogin = false;
                            this.isShowLogin = false;
                            this.handleReset();
                            this.$Message['success']({
                                background:true,
                                content: '登录成功'
                            });
                            /* 登录成功，通知组件刷新 */
                            EBUS.$emit('EVENT-USER-LOGIN', response.data);
                            if (this.$router.options.routes.length === 1) {
                                if (Number(response.data.is_admin) === 1 ) {
                                    defaultRoutes.push.apply(defaultRoutes, [adminRoutes, errorRoutes]);
                                    this.$router.addRoutes(adminRoutes);
                                } else {
                                    defaultRoutes.push.apply(defaultRoutes, errorRoutes);
                                }

                                this.$router.options.routes = defaultRoutes;
                                this.$router.addRoutes(errorRoutes);
                            }
                        }).catch(() => {
                            this.isDoLogin = false;
                            this.isLogin = false;
                        });
                    } else {
                        this.$Message.error('填写的信息有误，请检查');
                    }
                });
            },
            handleReset() {
                this.formData = {
                    email: '', password: ''
                }
            },
            logout() {
                window.localStorage.removeItem('gaea.user');
                this.isReload = true;
                this.$nextTick(() => {
                    this.isReload = false;
                });
                this.isLogin = false;
                this.$Message.info('已退出登陆');
            }
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
        top: 20px;
        float: right;
        right: 8px;
    }
    .lc-header {
        width: 100%;
        height: 64px;
        background: #0878ec;
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
</style>