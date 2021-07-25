const url = require('url')
const userM = require("../model/user.model")

function auth(req, res, next) {
    let myUrl = url.parse(req.url, true)
    userM.getById(myUrl.query.id, (err, result) => {
        if (err) res.status(500).json({ mess: 'erreur SQL' })
        else {
            if (result.length > 0 && result[0].cle === myUrl.query.cle) {
                req.idUser = myUrl.query.id
                next()
            }
            else {
                res.status(401).json({ mess: 'mauvais token' })
            }
        }
    })
}
module.exports = auth