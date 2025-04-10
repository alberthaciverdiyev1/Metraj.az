using Microsoft.AspNetCore.Mvc;
using Skutech.BusinessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.WebApi.Controllers;

[Route("api/[controller]")]
[ApiController]
public class BrandController : ControllerBase
{
    private readonly IBrandService _brandService;

    public BrandController(IBrandService brandService)
    {
        _brandService = brandService;
    }

    [HttpGet]
    public IActionResult List()
    {
        var result = _brandService.TGetList();

        return Ok(result);
    }

    [HttpGet("{id}")]
    public IActionResult Details(int id)
    {
        var result = _brandService.TGetElementById(id);

        return Ok(result);
    }

    [HttpPost]
    public IActionResult Add(Brand brand)
    {
        _brandService.TInsert(brand);
        return Ok();
    }

    [HttpPut]
    public IActionResult Update(Brand brand)
    {
        _brandService.TUpdate(brand);
        return Ok();
    }

    [HttpDelete("{id}")]
    public IActionResult Delete(int id)
    {
        var value = _brandService.TGetElementById(id);
        if (value != null) _brandService.TDelete(value);
        return Ok();
    }
}