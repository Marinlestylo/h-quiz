export default async function auth({next,userStore}){
    const user = await userStore.fetchUser();
    if(!user.id){
        return next({
            name:'root'
        })
    }

    return next()
}