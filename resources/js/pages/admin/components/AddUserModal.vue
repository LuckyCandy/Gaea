<template>
    
</template>

<script>
    import {addUser} from '../../../api/user';

    export default {
        name: "AddUserModal",
        data() {
            return {
                email: ''
            }
        },
        methods: {
            handleRender () {
                this.$Modal.confirm({
                    render: (h) => {
                        return h('Input', {
                            props: {
                                value: this.email,
                                autofocus: true,
                                placeholder: '请输入正确的电子邮箱'
                            },
                            on: {
                                input: (val) => {
                                    this.email = val;
                                }
                            }
                        })
                    },
                    okText: '提交',
                    cancelText: '取消',
                    onOk: () => {
                        addUser({email: this.email}).then(() => {
                            this.$Message.success('创建成功，密码已发送至对方邮箱');
                        }).catch(() => {});
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>