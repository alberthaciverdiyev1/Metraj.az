export async function logger(request, reply) {
    console.log(`[${new Date().toISOString()}] ${request.method} ${request.url}`)
}
