package ep.rest

import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import android.util.Log
import kotlinx.android.synthetic.main.activity_pivo_detail.*
import kotlinx.android.synthetic.main.content_pivo_detail.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.io.IOException

class PivoDetailActivity : AppCompatActivity(), Callback<Pivo> {

    private var pivo: Pivo? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_pivo_detail)
        setSupportActionBar(toolbar)

        supportActionBar?.setDisplayHomeAsUpEnabled(true)

        val id = intent.getIntExtra("ep.rest.id", 0)
        val app = application as PivomatApp
        if (id > 0) {
            PivoService.instance.get(id, app.cookie!!).enqueue(this)
        }






    }


    override fun onBackPressed() {
        finish()
    }

    override fun onResponse(call: Call<Pivo>, response: Response<Pivo>) {
        pivo = response.body()
        Log.i(TAG, "Got result: $pivo")

        if (response.isSuccessful) {
            tvOpis.text = "Znamka: " + pivo?.imeZnamke + "\n" + "Stil: " + pivo?.imeStila + "\n" + "Alkoholna vsebnost: " + pivo?.alkohol + "%\n" + "Neto količina: " + pivo?.kolicina + "l\n\n" + pivo?.opis +"\n\n" + "Cena: " + pivo?.cena + "€\n"
            toolbarLayout.title = pivo?.naziv

            fabVkosarico.setOnClickListener {
                val app = application as PivomatApp
                CartService.instance.insert(pivo?.id!!, 1, app.cookie!!).enqueue(object: Callback<String> {
                    override fun onResponse(call: Call<String>?, response: Response<String>?) {
                        Log.i("KOSARICA", response!!.body())
                    }

                    override fun onFailure(call: Call<String>?, t: Throwable?) {
                        Log.i("KOSARICA", "Neuspesno dodan element v kosarico")
                    }

                })
            }

        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Log.e(TAG, errorMessage)
            tvOpis.text = errorMessage
        }
    }

    override fun onFailure(call: Call<Pivo>, t: Throwable) {
        Log.w(TAG, "Error: ${t.message}", t)
    }

    companion object {
        private val TAG = PivoDetailActivity::class.java.canonicalName
    }
}

