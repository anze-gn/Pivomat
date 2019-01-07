package ep.rest

import java.io.Serializable

data class Pivo(
        val id: Int = 0,
        val aktiviran: Int = 0,
        val naziv: String = "",
        val idZnamka: Int = 0,
        val opis: String = "",
        val kolicina: Double = 0.0,
        val alkohol: Double = 0.0,
        val cena: Double = 0.0,
        val idStil: Int = 0,
        val imeZnamke: String = "",
        val imeStila: String = "",
        val uri: String = ""
) : Serializable
