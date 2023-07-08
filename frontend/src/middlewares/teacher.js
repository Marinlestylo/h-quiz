export default async function auth({next,userStore}){
    const user = await userStore.fetchUser();
    if(!user.id || !user.role.includes('staff')){
        return next({
            name:'root'
        })
    }

    return next()
}