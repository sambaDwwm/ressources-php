
const express = require('express')
const app = express()
const port = 3000
const db = require('./connect')
const userR = require('./router/user.router')
const taskR = require('./router/task.router')


app.use(express.json())
app.use(express.urlencoded({ extended: true }))


app.use("/api/user", userR)
app.use("/api/task", taskR)

app.get('/', (req, res) => {
    res.send('Hello World!')
})

app.listen(port, () => {
    console.log(`Example app listening at http://localhost:${port}`)
})
