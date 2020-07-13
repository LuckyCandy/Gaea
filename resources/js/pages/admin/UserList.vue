<template>
    <div class="user-list">
        <div class="header">
            <span class="header-title">用户列表</span>
            <Button type="primary" size="small" style="float: right;margin: 3px 0 0 10px" @click="refresh">刷新</Button>
            <Button type="success" size="small" style="float: right;margin-top: 3px;" @click="addNewUser">新增</Button>
        </div>
        <div class="search-content">
            <div class="search">
                <div class="search-item">
                    <span>用户名:&nbsp;</span>
                    <Input type="text" size="small" style="width: 120px" v-model="searchData.name"></Input>
                </div>
                <div class="search-item">
                    <span>邮箱:&nbsp;</span>
                    <Input type="text" size="small" style="width: 120px" v-model="searchData.email"></Input>
                </div>

                <div class="search-item" style="min-width: unset">
                    <span>禁用:&nbsp;</span>
                    <RadioGroup v-model="searchData.is_block" style="width: 100px;padding-top: 1px;">
                        <Radio label="是" value="1"></Radio>
                        <Radio label="否" value="2"></Radio>
                    </RadioGroup>
                </div>
                <Button type="success" size="small" style="float: left;margin: 2px 5px 0 0;" @click="resetSearchData">重置</Button>
                <Button type="primary" size="small" style="float: left;margin: 2px 0 0 5px;" @click="doSearch">查询</Button>
            </div>
            <div class="list">
                <Table stripe border :columns="columns" :data="list" :loading="isLoading">
                    <template slot-scope="{ row, index }" slot="status">
                        <RadioGroup v-model="rowStatus" style="width: 100px;padding-top: 1px;" v-if="editIndex === index">
                            <Radio label="禁用" value="1"></Radio>
                            <Radio label="正常" value="2"></Radio>
                        </RadioGroup>
                        <div v-else>
                            <span v-if="Number(row.is_block) === 1" style="color: red">禁用</span>
                            <span v-else style="color: green">正常</span>
                        </div>

                    </template>
                    <template slot-scope="{ row, index }" slot="action">
                        <div v-if="Number(row.is_admin) === 2">
                            <div v-if="editIndex !== index">
                                <a href="javascript:void(0)"  @click="enableRowEdit(row, index)">修改状态</a>
                                <span>|</span>
                                <a href="javascript:void(0)" @click="resetPassword(row)">重置密码</a>
                            </div>
                            <div v-else>
                                <a href="javascript:void(0)" @click="blockUser(row)">保存</a>
                                <span>|</span>
                                <a href="javascript:void(0)" @click="editIndex = -1">取消</a>
                            </div>
                        </div>
                        <div v-else>
                            <span style="color: gray">超级管理员</span>
                        </div>
                    </template>
                </Table>
            </div>
        </div>
        <div class="pager">
            <Page
                    :total="total"
                    show-total
                    :current="searchData.page"
                    :page-size="searchData.page_size"
                    @on-change="turnPage"
                    @on-page-size-change="changePageSize"
                    show-sizer>
            </Page>
        </div>
        <AddUserModal ref="addModal"></AddUserModal>
    </div>
</template>

<script>
    import {list, block, passwdReset} from "../../api/user";
    import AddUserModal from "./components/AddUserModal";

    export default {
        name: "UserList",
        components: {AddUserModal},
        data() {
            return {
                isBlocked: 0,
                editIndex: -1,
                rowStatus: 0,
                isLoading: false,
                columns: [
                    {
                        title: '序号',
                        key: 'id',
                        width: 65
                    },
                    {
                        title: '用户名',
                        key: 'name',
                        minWidth: 150
                    },
                    {
                        title: '邮箱',
                        key: 'email',
                        minWidth: 200
                    },
                    {
                        title: '状态',
                        slot: 'status',
                        width: 80
                    },
                    {
                        title: '创建时间',
                        key: 'created_at',
                        width: 180
                    },
                    {
                        title: '更新时间',
                        key: 'updated_at',
                        width: 180
                    },
                    {
                        title: '操作',
                        slot: 'action',
                        fixed: 'right',
                        width: 180,
                        align: 'center'
                    }
                ],
                list:[],
                total: 0,
                searchData: {
                    name: '',
                    email: '',
                    is_block: '',
                    page: 1,
                    page_size: 10
                }
            }
        },
        created() {
            this.doSearch();
        },
        methods: {
            resetSearchData() {
                this.searchData = {
                    name: '',
                    email: '',
                    is_block: '',
                    page: 1
                };
            },
            enableRowEdit(row, index) {
                this.rowStatus = row.is_block;
                this.editIndex = index;
            },
            doSearch() {
                this.isLoading = true;
                list(this.searchData).then(response => {
                    this.list = response.data.list;
                    this.total = response.data.total;
                    this.isLoading = false;
                }).catch((err) => {
                    console.log('err', err);
                    this.isLoading = false;
                });
            },
            turnPage(no) {
                this.searchData.page = no;
                this.doSearch();
            },
            changePageSize(size) {
                this.searchData.page_size = size;
                this.doSearch();
            },
            refresh() {
                this.resetSearchData();
                this.doSearch();
            },
            blockUser(user) {
                block(user.id).then(() => {
                    this.$Message.success('操作成功');
                    this.editIndex = -1;
                    this.doSearch();
                }).catch(() => {});
            },
            resetPassword(row) {
                passwdReset({email: row.email}).then(() => {
                    this.$Message.success("新密码已发送至<"+row.name+">的邮箱");
                }).catch(() => {})
            },
            addNewUser() {
                this.$refs.addModal.handleRender();
            }
        }
    }
</script>

<style scoped>
    .user-list {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .header {
        background: white;
        height: 40px;
        padding: 5px;
        position: absolute;
        top: 0;
        width: 100%;
    }
    .header-title {
        font-size: 15px;
        line-height: 30px;
        font-weight: bold;
        margin-left: 5px;
    }
    .search-content {
        position: absolute;
        top: 40px;
        width: 100%;
        bottom: 50px;
        padding: 5px;
    }
    .search {
        display: inline-block;
        width: 100%;
        border-radius: 3px;
        background: white;
        padding: 5px;
    }
    .search-item {
        display: flex;
        min-width: 180px;
        vertical-align: middle;
        float: left;
        height: 30px;
        padding: 2px 0;
        margin-right: 5px
    }
    .search-item span {
        white-space: nowrap;
        line-height: 22px;
    }
    .list {
        position: absolute;
        top: 50px;
        right: 5px;
        left: 5px;
        bottom: 10px;
        overflow: scroll;
    }
    .pager {
        position: absolute;
        bottom: 0;
        height: 50px;
        width: 100%;
        text-align: right;
        padding-right: 5px;
    }
</style>