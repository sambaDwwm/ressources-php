import React from 'react'

const tachesTests = [
    {
        titre: "tache 1",
        description: "desc tache 1",
        statut: 0
    },
    {
        titre: "tache 2",
        description: "desc tache 2",
        statut: 1
    },
    {
        titre: "tache 3",
        description: "desc tache 3",
        statut: 2
    },
    {
        titre: "tache 4",
        description: "desc tache 4",
        statut: 0
    }
]

class TableauTache extends React.Component {
    constructor(props) {
        super(props)
        this.state = { tabTaches: tachesTests }
    }
    getTableauParStatut(niv) {
        return this.state.tabTaches.filter((elem) => elem.statut === niv)
    }
    render() {
        return (
            <div className="conteneurTaches">
                <section id="tacheAFaire">
                    <h2>A faire</h2>
                    {this.getTableauParStatut(0).map((elem, key) => {
                        return console.log(elem)
                    })}
                </section>
                <section id="tacheEnCour">
                    <h2>En cours</h2>
                    {this.getTableauParStatut(1).map((elem, key) => {
                        return console.log(elem)
                    })}
                </section>
                <section id="tacheTermine">
                    <h2>Terminé</h2>
                    {this.getTableauParStatut(2).map((elem, key) => {
                        return console.log(elem)
                    })}
                </section>
            </div>
        )
    }
}
export default TableauTache;