using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class FlipScript : MonoBehaviour
{
    public SpriteRenderer spriteRenderer;
    public Sprite[] sides;
    int flipCount = 1;

    private BoxCollider2D stopClicks;

    public AudioSource audioSource;
    public AudioClip flippingSound;

    void Start()
    {
        //Checks if the card spawned face down, makes it flip the right way
        if (spriteRenderer.sprite == sides[1])
        {
            flipCount = 2;
        }
        //sets card face to the spawned face
        sides[0] = GameObject.Find("Spawner").GetComponent<Spawner>().chosenFace;


    }

    private void OnMouseDown()//starts flipping card when its clicked
    {
        StartCoroutine(WaitPlease(0.00001f, 1.0f));
        audioSource.PlayOneShot(flippingSound, 0.1f);
    }

    IEnumerator WaitPlease(float duration, float size)
    {

        while (size > 0.05)//shrinks card on x axis
        {
            stopClicks.enabled = false;//stops collider working until flipped to stop spam clicking
            size = size - 0.1f;
            transform.localScale = new Vector3(size, 1, 1);
            yield return new WaitForSeconds(duration);
        }

        spriteRenderer.sprite = sides[flipCount % 2];//changes the cards sprite to opposite face

        while (size < 0.99)//grows card on x axis
        {
            stopClicks.enabled = false;//stops collider working until flipped to stop spam clicking
            size = size + 0.09f;
            transform.localScale = new Vector3(size, 1, 1);
            yield return new WaitForSeconds(duration);
        }
        flipCount++;
        stopClicks.enabled = true;//reenables the collider to card can be flipped
    }

    private void Awake()
    {
        //assigning values
        spriteRenderer = GetComponent<SpriteRenderer>();
        audioSource = GetComponent<AudioSource>();
        stopClicks = GetComponent<BoxCollider2D>();
    }
}
