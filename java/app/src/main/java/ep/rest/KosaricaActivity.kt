package ep.rest

import android.content.Intent
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.widget.AdapterView
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_main_prijavljen.*
import kotlinx.android.synthetic.main.cartlist_element.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.io.IOException

class KosaricaActivity : AppCompatActivity(), Callback<List<CartItem>> {

    private var adapter: CartAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_kosarica)
        val app = application as PivomatApp
        adapter = CartAdapter(this)
        items.adapter = adapter
        items.onItemClickListener = AdapterView.OnItemClickListener { _, _, i, _ ->
            val cartItem = adapter?.getItem(i)
            if (cartItem != null) {
//                  val intent = Intent(this, PivoDetailActivity::class.java)
//                  intent.putExtra("ep.rest.id", cartItem.idPiva)
//                  startActivity(intent)
                btnIzbrisi.setOnClickListener {
                    CartService.instance.delete(cartItem.idPiva, app.cookie!!).enqueue(object: Callback<List<CartItem>>{
                        override fun onResponse(call: Call<List<CartItem>>?, response: Response<List<CartItem>>?) {
                            if (response!!.isSuccessful) {
                                container.setOnRefreshListener { CartService.instance.getAll(app.cookie!!).enqueue(this) }
                                Log.i("KOSARICA", "Uspesno izbrisan element")
                            }


                        }

                        override fun onFailure(call: Call<List<CartItem>>?, t: Throwable?) {
                            Log.i("KOSARICA", "Neuspesno izbrisan element")
                        }
                    })
                }
            }
        }


        CartService.instance.getAll(app.cookie!!).enqueue(this)
    }


    override fun onResponse(call: Call<List<CartItem>>?, response: Response<List<CartItem>>?) {
        val hits = response!!.body()

        if (response.isSuccessful) {
            Log.i("KOSARICA", "Uspesno prikazana kosarica")
            adapter?.clear()
            adapter?.addAll(hits)
        } else {
            val errorMessage = try {
                "An error occurred: ${response.errorBody().string()}"
            } catch (e: IOException) {
                "An error occurred: error while decoding the error message."
            }

            Toast.makeText(this, errorMessage, Toast.LENGTH_SHORT).show()

        }
        container.isRefreshing = false
    }

    override fun onFailure(call: Call<List<CartItem>>?, t: Throwable?) {
        container.isRefreshing = false
        Log.i("KOSARICA", "Nemorem prikazati kosarice")
    }
}
