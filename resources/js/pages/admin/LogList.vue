<template>
    <div class="user-list">
        <div class="header">
            <span class="header-title">操作日志</span>
            <Button type="primary" size="small" style="float: right;margin: 3px 0 0 10px" @click="refresh">刷新</Button>
        </div>
        <div class="search-content">
            <div class="search">
                <div class="search-item">
                    <span>操作人:&nbsp;</span>
                    <Input type="text" size="small" style="width: 120px" v-model="searchData.user"></Input>
                </div>
                <div class="search-item">
                    <span>类型:&nbsp;</span>
                    <Select v-model="searchData.type" size="small" style="width:100px">
        				<Option value="1" key="1">登陆</Option>
        				<Option value="2" key="2">禁用</Option>
    				</Select>
                </div>

                <Button type="success" size="small" style="float: left;margin: 2px 5px 0 0;" @click="resetSearchData">重置</Button>
                <Button type="primary" size="small" style="float: left;margin: 2px 0 0 5px;" @click="doSearch">查询</Button>
            </div>
            <div class="list">
                <Table stripe border :columns="columns" :data="list" :loading="isLoading">
                    <template slot-scope="{ row, index }" slot="user-name">
                        <span>{{ row.user.name }}</span>
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
    </div>
</template>

<script>
    import {list} from "../../api/log";

    export default {
        name: "UserList",
        components: {},
        data() {
            return {
                isLoading: false,
                columns: [
                    {
                        title: '序号',
                        key: 'id',
                        width: 65
                    },
                    {
                        title: '用户名',
                        slot: 'user-name',
                        minWidth: 150,
                    },
                    {
                        title: '操作类型',
                        key: 'type',
                        width: 200
                    },
                    {
                        title: '描述',
                        key: 'desc',
                        minWidth: 200
                    },
                    {
                        title: '操作时间',
                        key: 'created_at',
                        width: 180
                    }
                ],
                list:[],
                total: 0,
                searchData: {
                    user: '',
                    type: '',
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
                this.searchData.user =  '';
                this.searchData.type =  '';
            },
            doSearch() {
                this.isLoading = true;
                list(this.searchData).then(response => {
                    this.list = response.data.list;
                    this.total = response.data.total;
                    this.isLoading = false;
                }).catch((err) => {
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