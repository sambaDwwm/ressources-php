const userM = require("../model/user.model")
const express = require('express')
const auth = require("../middleware/auth")

let router = express.Router()

router.get('/', auth, (req, res) => {
    userM.getAll((err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.get('/:id', auth, (req, res) => {
    userM.getById(req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.post('/connect', (req, res) => {
    userM.getByLogin(req.body.login, (err, result) => {
        if (err) res.status(500).json(err)
        else {
            if (result.length > 0 && result[0].mdp === req.body.mdp) {
                res.status(200).json({ id: result[0].id_user, cle: result[0].cle })
            }
            else {
                res.status(401).json({ mess: 'identifiant ou mot de passe erronnés' })
            }
        }
    })
})
router.post('/', (req, res) => {
    userM.add(req.body.login, req.body.mdp, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.put('/:id', auth, (req, res) => {
    userM.update(req.body.login, req.body.mdp, req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
router.delete('/:id', auth, (req, res) => {
    userM.delete(req.params.id, (err, result) => {
        if (err) res.status(500).json(err)
        else res.status(200).json(result)
    })
})
module.exports = router