package ep.rest

import android.icu.text.DecimalFormat
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_kosarica.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.io.IOException
import java.math.RoundingMode
import java.util.*

class KosaricaActivity : AppCompatActivity(), Callback<List<CartItem>> {

    private var adapter: CartAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_kosarica)
        val app = application as PivomatApp
        adapter = CartAdapter(this)
        items.adapter = adapter
//        items.onItemClickListener = AdapterView.OnItemClickListener { _, _, i, _ ->
//            val cartItem = adapter?.getItem(i)
//            Log.i("KOSARICA", "Izbrana postavka")
//            if (cartItem != null) {
//                  val intent = Intent(this, PivoDetailActivity::class.java)
//                  intent.putExtra("ep.rest.id", cartItem.id)
//                  startActivity(intent)
//                btnIzbrisi.setOnClickListener {
//                    CartService.instance.delete(cartItem.id, app.cookie!!).enqueue(object: Callback<List<CartItem>>{
//                        override fun onResponse(call: Call<List<CartItem>>?, response: Response<List<CartItem>>?) {
//                            if (response!!.isSuccessful) {
//                                container.setOnRefreshListener { CartService.instance.getAll(app.cookie!!).enqueue(this) }
//                                Log.i("KOSARICA", "Uspesno izbrisan element")
//                            } else {
//                                Log.i("KOSARICA", "Neuspesno izbrisan element")
//                            }
//
//
//                        }
//
//                        override fun onFailure(call: Call<List<CartItem>>?, t: Throwable?) {
//                            Log.i("KOSARICA", "Neuspesno izbrisan element")
//                        }
//                    })
//                }
//            }
//        }
        container.setOnRefreshListener { CartService.instance.getAll(app.cookie!!).enqueue(this) }


        CartService.instance.getAll(app.cookie!!).enqueue(this)
    }


    override fun onResponse(call: Call<List<CartItem>>?, response: Response<List<CartItem>>?) {
        val hits = response!!.body()

        if (response.isSuccessful) {
            Log.i("KOSARICA", "Uspesno prikazana kosarica")
            adapter?.clear()
            adapter?.addAll(hits)
            val sCena = koncnaCena(hits)
            val app = application as PivomatApp
            app.skupnaCena = sCena

            tvSkupnaCena.text = String.format(Locale.ENGLISH, "Skupna cena: %.2f€", sCena)
            container.isRefreshing = false

        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show()

        }
    }

    override fun onFailure(call: Call<List<CartItem>>?, t: Throwable?) {
        Log.i("KOSARICA", "Nemorem prikazati kosarice")
        container.isRefreshing = false
    }

    fun koncnaCena(list: List<CartItem>): Double {
        var vsota = 0.0
        for (e in list) {
            vsota += (e.cena * e.kol)
        }
        return vsota
    }
}
